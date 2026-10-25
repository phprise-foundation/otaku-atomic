# AGENTS.md

> **Before you start:** read [OTAKU Manifesto](https://otakumanifesto.org/manifesto/) — it holds
> the unbreakable rules. For how to apply a rule, see [OTAKU Guide](https://otakumanifesto.org/guide/).
> For any git operation, see [`docs/GITFLOW.md`](docs/GITFLOW.md).

## What this project is

An OTAKU **Atomic** repository — the smallest unit of granularity. It fulfills a
single, specific contract, with a flat structure (one level of directories inside
`src/`). Read `composer.json` for the exact PHP and dependency versions.

## Code rules (non-negotiable)

The 9 Object Calisthenics rules of [OTAKU Manifesto](https://otakumanifesto.org/manifesto/) are
mandatory. In short:

1. One level of indentation per method.
2. No `else`.
3. Wrap primitives and strings (value objects).
4. First-class collections.
5. One dot per line (Law of Demeter).
6. Don't abbreviate.
7. Small entities (≤50 lines per class, ≤10 files per package).
8. At most 2 instance variables per class.
9. No getters/setters/properties.

## Conventions

- **Strict types:** `declare(strict_types=1);` in every file.
- **No comments** in code. The code must explain itself; the "why" goes in the commit or the GUIDE.
- **Attributes** instead of annotations (`#[Override]`).
- **Interfaces** for public methods.
- **No `switch/case`.** Use `match` or polymorphism.
- **PHP tag:** only `<?php` on the first line, `declare(strict_types=1);` on the
  third, namespace, and **never** close with `?>`.
- **Cleanliness:** remove unused imports, comments, variables and private functions.

## Workflow

- Run tests with `vendor/bin/phpunit`.
- Run static analysis with `vendor/bin/psalm`.

## Discover, don't guess

- Read the installed code under `vendor/` before assuming an API.
- Use grep/search to locate what already exists before creating something new.
- If the task doesn't specify something, **ask** instead of guessing.
