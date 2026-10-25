# GITFLOW.md — Git workflow

> Guide to the Git flows of this project: how to create branches, commit, open a
> PR, merge, version and publish. **Follow this document** when contributing.
>
> In case of conflict with the [OTAKU Manifesto](https://otakumanifesto.org/manifesto/), the philosophy wins.

---

## 1. Overview

We adopt a **lean** flow based on short-lived branches and PRs into `main`. We do
not use classic gitflow (`develop` + `release/*` + `hotfix/*`).

```
main ──●────────────────●───────────────●──────────►
        \              / \             /
         ●──●──●──────●   ●──●──●─────●
        feat/...          fix/...
```

- **`main`** is the only permanent, always-stable branch.
- All work starts from a **short branch** (`feat/*` or `fix/*`).
- Work reaches `main` **only via PR**.
- Each relevant change produces a **semver tag** and a **release**.

---

## 2. Step by step

### 2.1 Update `main`

```bash
git checkout main
git pull origin main
```

### 2.2 Create the working branch

From the **updated** `main`:

```bash
git checkout -b feat/short-description   # new feature
git checkout -b fix/short-description    # bug fix
```

Naming rules:

- `feat/<description>` — new feature
- `fix/<description>` — bug fix
- Use `kebab-case`, descriptive and short.

### 2.3 Implement and stage

```bash
git add <files>
```

> **Remember:** before implementing, read the philosophy and the state documents.
> When done, update STATE, ROADMAP, ARCHITECTURE and README.

### 2.4 Commit (Conventional Commits)

Format:

```
<type>(<ID>): <imperative description>
```

Examples:

```
feat(123): add endpoint
fix(124): correct validation
docs(125): update README
```

Common types: `feat`, `fix`, `docs`, `refactor`, `test`, `chore`.

- `<ID>` is the task identifier; omit it when there is none.
- The description is short, imperative, lowercased.

### 2.5 Push and PR

```bash
git push -u origin feat/short-description

gh pr create \
  --base main \
  --title "feat(123): short description" \
  --body "## What changed
- ...

## How to test
- ...

## Checklist
- [ ] Tests green
- [ ] Documents updated"
```

### 2.6 Check conflicts and merge

```bash
gh pr view <number> --json mergeable,mergeStateStatus
```

If there is **no conflict**, merge and delete the branch:

```bash
gh pr merge <number> --merge --delete-branch
```

If there **is** a conflict, resolve locally:

```bash
git checkout feat/short-description
git fetch origin
git rebase origin/main
# resolve conflicts
git add <files>
git rebase --continue
git push --force-with-lease
```

### 2.7 Update local `main`

```bash
git checkout main
git pull origin main
```

---

## 3. Versioning (Semantic Versioning)

After merging, create a **tag** following [SemVer](https://semver.org/):

| Change | Bump | Example |
|---|---|---|
| `fix` | **patch** | `v1.0.0` → `v1.0.1` |
| `feat` | **minor** | `v1.0.0` → `v1.1.0` |
| *breaking change* | **major** | `v1.0.0` → `v2.0.0` |

> **Tag policy: always prefixed with `v`** (e.g. `v1.0.0`).

### 3.0 Development version (`0.y.z`)

While the API is not stable, version in **`0.y.z`**. **`v1.0.0`** is only declared
when all roadmap phases are done and the API is considered stable.

### 3.0.1 Tag immutability

From `v0.1.0`, **tags are immutable**: never delete or rewrite a published tag.

### 3.1 Release policy

**One release per tag.** Each published tag produces its own release with its own
notes (`gh release create <tag> --generate-notes`).

```bash
git describe --tags --abbrev=0
git tag v0.1.1
git push origin v0.1.1
```

---

## 4. Publication

Use [`bin/publish.sh`](../bin/publish.sh) to create the GitHub repository, push the
branch, create the tag and the release.

```bash
./bin/publish.sh --tag v0.1.1 --repo owner/repository --public
```

---

## 5. Golden rules

- **Never** commit directly to `main`.
- **Never** commit secrets (`.env`, keys, tokens).
- **Always** open a PR, even for small changes.
- **Always** delete the branch after merge (`--delete-branch`).
- **Always** update the state documents before opening the PR.
