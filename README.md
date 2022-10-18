# Installation

-   `composer install` Laravel application init.
-   `npm install` Inertia, React, application init.

# Folder structure

-   `../inertia` Public folder.
-   `base-laravel/inertia` Main application folder.

# Config

-   Because laravel [log-viewer](https://github.com/opcodesio/log-viewer) use live wire, in `.env` should add `WIRE_URL=/<subfolder>`</subfolder> if main project run in sub folder directory

# Commands

## Vendor Commands

-   `laravel backup` - Run Laravel backup. See documentation here [Backup](https://spatie.be/docs/laravel-backup/v8/introduction).

## Custom Commands

-   `php artisan manifest:generate` - Generate icon asset and manifest for PWA.
-   `npm run start` - Vite build watch
-   `npm run dev` - Vite dev with hot file
-   `npm run build` - Vite buidl resources and ssr
-   `npm run init` - Init application will run `php artisan app:install` and `npm run build`
-   `npm run reset` - Reset application configuration will run `php artisan app:install -R` and `npm run build`

# Known bug

1. Duplicate url

Url will be duplicate in inertia response. You have to modify inertia file. See thread here [Inertia Response](https://github.com/inertiajs/inertia-laravel/pull/446/commits/910103db091a3a163bfc06afe08ce2d4709ddddb).
