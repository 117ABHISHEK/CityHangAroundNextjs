<!-- BEGIN:nextjs-agent-rules -->

# This is NOT the Next.js you know

This version has breaking changes — APIs, conventions, and file structure may all differ from your training data. Read the relevant guide in `node_modules/next/dist/docs/` (resolved from this file's directory; in monorepos the `next` package may not be visible from the repo root) before writing any code. Heed deprecation notices.

This block is written and re-added by `next dev` — verify at `node_modules/next/dist/server/lib/generate-agent-files.js`. Removing it from a diff only re-creates the uncommitted change; committing it with your work keeps the tree clean.

<!-- END:nextjs-agent-rules -->

## Frontend Reference Rules

- Use the root-level `newui.html` as the visual and interaction reference for all frontend UI work, including HTML, CSS, JavaScript, React, layout, responsive behavior, animations, icons, spacing, colors, and component structure.
- Treat `newui.html` as read-only reference material. Never edit, reformat, truncate, or replace it.
- Implement frontend changes only inside `frontend/`. Do not enter or modify `backend/` for frontend tasks.
- Preserve the reference design while adapting its structure into maintainable Next.js components, colocated stylesheets, and the project's shared UI/icon patterns.
