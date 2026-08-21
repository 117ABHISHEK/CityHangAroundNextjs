# CityHangAroundNextjs — Team Git & Local Setup Guide

> **Repository:** https://github.com/117ABHISHEK/CityHangAroundNextjs.git

---

## 1. Project Team Branch Structure

The repository uses one stable main branch and one personal development branch for each team member.

| Branch | Developer / Purpose |
|--------|-------------------|
| `main` | Abhishek / Stable production-ready code |
| `harsh` | Harsh |
| `arpit` | Arpit |
| `subham` | Subham |
| `priyanka` | Priyanka |
| `tasmiya` | Tasmiya  |

Each developer works only on their own branch and submits a Pull Request (PR) to main when the work is ready.

---

## 2. Prerequisites

- Git installed.
- Node.js and npm installed.
- GitHub account added as a repository collaborator.
- VS Code or another code editor.
- GitHub authentication configured in VS Code/Git.
- Required environment variables or backend-service access.

---

## 3. Clone the Repository

```bash
git clone https://github.com/117ABHISHEK/CityHangAroundNextjs.git
cd CityHangAroundNextjs
git remote -v
```

**Expected remote:**

```
origin https://github.com/117ABHISHEK/CityHangAroundNextjs.git (fetch)
origin https://github.com/117ABHISHEK/CityHangAroundNextjs.git (push)
```

---

## 4. GitHub Authentication in VS Code

Every team member must authenticate Git with the GitHub account that has repository access. If VS Code/Git is not authenticated, pushing may fail with authentication or permission errors.

**Recommended VS Code setup:**

1. Open VS Code.
2. Open the **Accounts** icon.
3. Select **Sign in with GitHub**.
4. Complete the browser authentication.
5. Reopen the VS Code terminal if needed.
6. Verify the remote:

```bash
git remote -v
```

If Git asks for a username and password over HTTPS, use a **GitHub Personal Access Token (PAT)** instead of the normal GitHub password. Never share a token or commit it to the repository.

If old credentials cause authentication errors, remove the old GitHub credential from **Windows Credential Manager** and authenticate again.

---

## 5. Create the Personal Branch

**Harsh**

```bash
git switch -c harsh
git push -u origin harsh
```

**Arpit**

```bash
git switch -c arpit
git push -u origin arpit
```

**Subham**

```bash
git switch -c subham
git push -u origin subham
```

**Priyanka**

```bash
git switch -c priyanka
git push -u origin priyanka
```

**Tasmiya**

```bash
git switch -c tasmiya
git push -u origin tasmiya
```

**Verify with:**

```bash
git branch
```

The `*` symbol should appear next to your personal branch.

---

## 6. If the Branch Is Not Published on GitHub

If your personal branch is not present on GitHub, create and publish it:

```bash
git switch -c tasmiya
git push -u origin tasmiya
```

Replace `tasmiya` with your own branch name.

If the branch already exists locally but is not published:

```bash
git switch tasmiya
git push -u origin tasmiya
```

Check remote branches with:

```bash
git fetch origin
git branch -r
```

---

## 7. Local Next.js Setup

```bash
cd frontend
npm install
npm run dev
```

Normally available at http://localhost:3000

If `.env.example` exists:

```bash
copy .env.example .env.local
```

> Never commit `.env.local` or secrets.

---

## 8. Daily Development Workflow

Start on your personal branch:

```bash
git switch <your-branch>
git pull origin <your-branch>
```

After changes:

```bash
git status
git add .
git commit -m "Describe your change"
git push origin <your-branch>
```

---

## 9. Daily Update: Get Latest main Code

Whenever new code is pushed or merged into main, update your personal branch using this team-standard workflow:

```bash
git fetch origin
git merge origin/main
git push origin <your-branch>
```

**Example for Tasmiya:**

```bash
git switch tasmiya
git fetch origin
git merge origin/main
git push origin tasmiya
```

This fetches the latest remote information, merges the latest remote main into the current personal branch, and pushes the updated personal branch to GitHub.

---

## 10. Merge Conflicts

```bash
git status
# Resolve conflicts in VS Code
git add .
git commit -m "Resolve merge conflicts with main"
git push origin <your-branch>
```

Review conflicts carefully; do not blindly overwrite another developer's work.

---

## 11. Pull Request Workflow

1. Complete feature/fix on personal branch.
2. Push personal branch to GitHub.
3. Open a PR from personal branch into `main`.
4. Abhishek/team reviewer checks the code.
5. Fix review comments if required.
6. Merge into main only after approval.
7. Delete the branch only if no longer needed.

---

## 12. Important Team Rules

- **Do not work directly on main.**
- **Do not push directly to main.**
- Use your personal branch.
- Use the daily `fetch → merge origin/main → push` workflow.
- Write clear commit messages.
- Open a PR before merging into main.
- Never share GitHub PATs.
- Never commit tokens, passwords, API keys, `.env`, `.env.local`, `node_modules`, or `.next`.
- Do not overwrite another developer's work without discussion.
- Keep commits focused.

---

## 13. Recommended Branch Structure

```
main
├── harsh
├── arpit
├── subham
├── priyanka
└── tasmiya
```

---

## 14. Common Git Commands

| Purpose | Command |
|---------|---------|
| Check status | `git status` |
| List local branches | `git branch` |
| List remote branches | `git branch -r` |
| Switch branch | `git switch <branch-name>` |
| Create branch | `git switch -c <branch-name>` |
| Fetch remote updates | `git fetch origin` |
| Pull personal branch | `git pull origin <branch-name>` |
| Merge latest main | `git merge origin/main` |
| Stage changes | `git add .` |
| Commit | `git commit -m "message"` |
| Push current branch | `git push` |
| Publish new branch | `git push -u origin <branch-name>` |
| History | `git log --oneline` |

---

## 15. Quick Setup Checklist

- [ ] Git installed
- [ ] Repository cloned
- [ ] GitHub access confirmed
- [ ] GitHub authenticated in VS Code
- [ ] Personal branch created
- [ ] Personal branch published
- [ ] Dependencies installed
- [ ] Environment configured
- [ ] Project runs locally
- [ ] Working on personal branch
- [ ] Latest main merged into personal branch
- [ ] PR process understood

---

## 16. Final Team Workflow

```
Clone repository
        ↓
Authenticate GitHub in VS Code
        ↓
Create / publish personal branch
        ↓
Install dependencies
        ↓
Configure environment
        ↓
Develop locally
        ↓
Daily update:
  git fetch origin
  git merge origin/main
  git push origin <your-branch>
        ↓
Continue development
        ↓
Commit and push
        ↓
Pull Request → main
        ↓
Code Review
        ↓
Merge into main
        ↓
Repeat daily update workflow
```
