# GST POS

GST POS — Laravel point-of-sale app with Tailwind UI.

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
```

Default seeded user (if using `DatabaseSeeder`): see `database/seeders/DatabaseSeeder.php`.
