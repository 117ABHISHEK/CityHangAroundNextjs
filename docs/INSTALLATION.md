# Project Installation Guide

This workspace is organized into three root folders:

- `frontend` - Next.js application
- `backend` - Laravel 12 application
- `docs` - documentation

## Root cleanup

The root folder has been kept clean. Only the following items remain at the workspace root:

- `frontend`
- `backend`
- `docs`
- `.gitignore`
- `tools`

The `tools` folder contains a local Composer PHAR (`tools/composer.phar`) because Composer was not available globally on the system.

## Tool installation

These tools are required to work with this project:

- Node.js and npm (for `frontend`)
- XAMPP PHP (`C:\xampp\php\php.exe`) (for `backend`)
- Composer (installed locally as `tools/composer.phar`)

### Install Node.js and npm

Download and install Node.js from:

```bash
https://nodejs.org/
```

After installation, verify:

```bash
node -v
npm -v
```

### Install XAMPP PHP

Install XAMPP from:

```bash
https://www.apachefriends.org/
```

Then verify PHP exists at `C:\xampp\php\php.exe`:

```bash
"C:\xampp\php\php.exe" -v
```

### Install Composer locally

If Composer is not installed globally, use XAMPP PHP to install it locally in the project:

```bash
cd "CityHangAroundNextjs"
"C:\xampp\php\php.exe" -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
"C:\xampp\php\php.exe" composer-setup.php
Move-Item composer.phar tools\composer.phar
Remove-Item composer-setup.php
```

Verify Composer:

```bash
"C:\xampp\php\php.exe" tools\composer.phar --version
```

## Frontend setup

The `frontend` folder contains a Next.js app created with:

```bash
npx create-next-app@latest frontend --use-npm --yes
```

### Run the frontend

```bash
cd frontend
npm install
npm run dev
```

## Backend setup

The `backend` folder contains a Laravel 12 project created with XAMPP PHP.

### Laravel commands

Use XAMPP PHP with the local Composer binary:

```bash
cd backend
php ..\tools\composer.phar install
php artisan serve
```

If you need global Composer later, install Composer and then you can run Laravel commands without `tools/composer.phar`.

## Notes about XAMPP and Composer

- XAMPP PHP is installed at `C:\xampp\php\php.exe`
- `composer.phar` was downloaded to workspace root temporarily and moved to `tools/composer.phar`
- The root `.gitignore` ignores this local Composer binary so it is not committed

## Optional next steps

- Add `tools/` to the root `.gitignore` if you do not want the folder tracked
- Configure XAMPP Apache to serve the `backend/public` folder if you want the Laravel app available through a browser on localhost
- Add README sections for frontend/backend commands and app-specific notes
