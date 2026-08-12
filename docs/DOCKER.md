# Docker Deployment

This repo can be run as a single Docker container for local testing and simple deployments.

## Build the image

From the repository root:

```powershell
cd "Repo\CityHangAroundNextjs"
docker build -t cityhangaroundnextjs .
```

## Run the container

Expose the frontend port `3000` and the backend port `9000`:

```powershell
docker run --rm -p 3000:3000 -p 9000:9000 cityhangaroundnextjs
```

## Access the app

- Frontend: `http://localhost:3000`
- Backend PHP server: `http://localhost:9000`

## Notes

- The current single-container setup is intended for development or demo use.
- For production, use a proper reverse proxy and a production database instead of SQLite.
- If you need both services on a single public port, add a reverse proxy configuration (Nginx) and expose only that port.

## Using Coolify

This repo is ready for Coolify deployment via the root `Dockerfile`.

1. Push this repository to GitHub, GitLab, or another Git provider.
2. In Coolify, create a new app and choose the Dockerfile build method.
3. Set the build context to the repository root.
4. Use the default build command because the Dockerfile already installs and builds both apps.
5. Expose port `3000` for the frontend. If you want the backend available separately, also expose port `9000`.

### Environment variables

For Coolify, add these production environment variables:

- `APP_ENV=production`
- `APP_KEY=<your-app-key>`
- `APP_URL=https://your-domain.example`
- `DB_CONNECTION=sqlite`
- `DB_DATABASE=/var/www/backend/database/database.sqlite`

If you use a hosted database service, switch to `DB_CONNECTION=pgsql` or `mysql` and configure `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.

## Root cleanup notes

These root files are not required to deploy the app:

- `.next` and `frontend/.next` are build artifacts and should be ignored.
- `package-lock.json` at the repo root is not used by the frontend or backend.
- `newui.html` is a design/reference file, not required for runtime.
- `nginx/` is empty and can be removed unless you plan to add custom proxy configs.
- `tools/composer.phar` is a local Composer helper for development, not required for the Docker build.
