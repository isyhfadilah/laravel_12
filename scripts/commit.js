import { spawnSync } from 'node:child_process';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const git = (...args) => spawnSync('git', ['-c', `safe.directory=${root}`, ...args], {
    cwd: root,
    encoding: 'utf8',
    stdio: ['ignore', 'pipe', 'inherit'],
});

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

    const commonDirectory = (() => {
        const segments = normalized.map((file) => file.split('/').slice(0, -1));
        const shared = [];
        for (let i = 0; segments.length && segments.every((parts) => parts[i] === segments[0][i]); i++) {
            shared.push(segments[0][i]);
        }
        return shared.at(-1) || '';
    })();
    const scope = words(commonDirectory.split('.').slice(0, -1).join('.') || commonDirectory)
        .replace(/\s+/g, '-')
        .replace(/[^a-z0-9/-]/g, '')
        .slice(0, 30);

    const fileLabels = paths.map((file) => {
        const name = path.posix.basename(file.replaceAll('\\', '/')).replace(/\.[^.]+$/, '');
        return words(name);
    });
    let description;
    if (fileLabels.length <= 3) {
        description = `update ${fileLabels.join(', ')}`;
    } else {
        const area = scope ? scope.replaceAll('-', ' ') : 'project files';
        description = `update ${area} files`;
    }
    description = description.slice(0, 72).replace(/[.!?]+$/, '');
    return `${type}${scope ? `(${scope})` : ''}: ${description}`;
}

const add = git('add', '--all');
if (add.status !== 0) process.exit(add.status ?? 1);

const staged = git('diff', '--cached', '--quiet');
if (staged.status === 0) {
    console.log('Tidak ada perubahan untuk di-commit.');
    process.exit(0);
}
if (staged.status !== 1) process.exit(staged.status ?? 1);

const names = git('diff', '--cached', '--name-only');
if (names.status !== 0) process.exit(names.status ?? 1);
const paths = names.stdout.trim().split(/\r?\n/).filter(Boolean);
const message = inferCommit(paths);

console.log(`Commit otomatis: ${message}`);
console.log(git('diff', '--cached', '--stat').stdout);
const commit = spawnSync('git', ['-c', `safe.directory=${root}`, 'commit', '-m', message], {
    cwd: root,
    stdio: 'inherit',
});
process.exit(commit.status ?? 1);
