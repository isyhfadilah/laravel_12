import { watch } from 'node:fs';
import { spawnSync } from 'node:child_process';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const debounceMs = 1800;
const ignored = new Set(['.git', 'node_modules', 'vendor', 'storage', 'bootstrap/cache', 'public/build']);
const pendingPaths = new Set();
let timer;
let busy = false;

function git(...args) {
    return spawnSync('git', ['-c', `safe.directory=${root}`, ...args], {
        cwd: root,
        encoding: 'utf8',
        stderr: 'inherit',
    });
}

function gitText(...args) {
    const result = git(...args);
    return { status: result.status, stdout: result.stdout ?? '' };
}

function shouldIgnore(file) {
    const normalized = file.replaceAll('\\', '/').replace(/^\.\//, '');
    if (!normalized) return true;
    if (normalized === '.env' || normalized.startsWith('.env.')) return true;
    return normalized.split('/').some((part, index, parts) => {
        const prefix = parts.slice(0, index + 1).join('/');
        return ignored.has(part) || ignored.has(prefix);
    });
}

function words(value) {
    return value.replace(/[_-]+/g, ' ').replace(/\s+/g, ' ').trim();
}

function inferCommit(paths) {
    const migration = paths.length === 1
        ? paths[0].match(/(?:^|\/)(?:\d+_)?(add|create|remove|drop|rename)_(.+?)_to_(.+?)_table\.php$/i)
        : null;
    if (migration) {
        const [, action, rawItems, rawTable] = migration;
        const table = words(rawTable).replace(/\btable\b/i, '').trim();
        const items = words(rawItems);
        const verb = { add: 'add', create: 'create', remove: 'remove', drop: 'drop', rename: 'rename' }[action.toLowerCase()];
        const object = action.toLowerCase() === 'add' || action.toLowerCase() === 'remove'
            ? `${items} ${items.includes(' ') ? 'columns' : 'column'}`
            : `${table} table`;
        const type = action.toLowerCase() === 'remove' || action.toLowerCase() === 'drop' ? 'refactor' : 'feat';
        return `${type}(${table}): ${verb} ${object}`.replace(/\s+/g, ' ');
    }

    const normalized = paths.map((file) => file.replaceAll('\\', '/').toLowerCase());
    const allDocs = normalized.every((file) => /(^|\/)(readme|changelog|license)|\.(md|mdx|rst|txt)$/.test(file));
    const allTests = normalized.every((file) => /(^|\/)(tests?|specs?)(\/|\.)/.test(file));
    const type = allDocs ? 'docs' : allTests ? 'test' : 'chore';
    const directories = normalized.map((file) => file.split('/').slice(0, -1));
    const shared = [];
    for (let i = 0; directories.length && directories.every((parts) => parts[i] === directories[0][i]); i++) {
        shared.push(directories[0][i]);
    }
    const scope = words(shared.at(-1) || '').replace(/\s+/g, '-').replace(/[^a-z0-9/-]/g, '').slice(0, 30);
    const labels = paths.map((file) => words(path.posix.basename(file.replaceAll('\\', '/')).replace(/\.[^.]+$/, '')));
    const description = (labels.length <= 3
        ? `update ${labels.join(', ')}`
        : `update ${scope ? scope.replaceAll('-', ' ') : 'project'} files`).slice(0, 72).replace(/[.!?]+$/, '');
    return `${type}${scope ? `(${scope})` : ''}: ${description}`;
}

async function commitPending() {
    if (busy) return;
    busy = true;
    const paths = [...pendingPaths];
    pendingPaths.clear();
    try {
        const usable = paths.filter((file) => !shouldIgnore(file));
        const addArgs = usable.length ? ['add', '--all', '--', ...usable] : ['add', '--all'];
        const add = git(...addArgs);
        if (add.status !== 0) return;

        const staged = gitText('diff', '--cached', '--quiet');
        if (staged.status === 0) return;
        if (staged.status !== 1) return;

        const names = gitText('diff', '--cached', '--name-only');
        if (names.status !== 0) return;
        const commitPaths = names.stdout.trim().split(/\r?\n/).filter(Boolean);
        const message = inferCommit(commitPaths);
        console.log(`\nPerubahan: ${commitPaths.join(', ')}`);
        console.log(`Commit otomatis: ${message}`);
        console.log(git('diff', '--cached', '--stat').stdout ?? '');
        const result = git('commit', '-m', message);
        if (result.stdout) process.stdout.write(result.stdout);
        if (result.status !== 0) console.error('Commit gagal. Perubahan staged akan dicoba lagi saat ada perubahan berikutnya.');
    } finally {
        busy = false;
        if (pendingPaths.size) scheduleFlush();
    }
}

function scheduleFlush() {
    clearTimeout(timer);
    timer = setTimeout(() => void commitPending(), debounceMs);
}

console.log('Memeriksa perubahan yang sudah ada...');
const initialStatus = gitText('status', '--porcelain');
if (initialStatus.status !== 0) process.exit(initialStatus.status ?? 1);
if (initialStatus.stdout.trim()) {
    pendingPaths.add('.');
    await commitPending();
}

let watcher;
try {
    watcher = watch(root, { recursive: true }, (_event, filename) => {
        if (!filename) {
            pendingPaths.add('.');
        } else {
            const file = filename.toString().replaceAll('\\', '/');
            if (shouldIgnore(file)) return;
            pendingPaths.add(file);
        }
        console.log(`Perubahan terdeteksi: ${filename?.toString() ?? '(nama file tidak tersedia)'}`);
        scheduleFlush();
    });
} catch (error) {
    console.error(`Tidak bisa memulai watcher: ${error.message}`);
    process.exit(1);
}

console.log(`Watcher aktif di ${root}. Commit dibuat otomatis setelah ${debounceMs / 1000} detik tanpa perubahan. Tekan Ctrl+C untuk berhenti.`);
process.on('SIGINT', () => {
    clearTimeout(timer);
    watcher.close();
    console.log('\nWatcher dihentikan.');
    process.exit(0);
});
