# myPos

Laravel POS app with Tailwind login UI.

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
```

Default seeded user (if using `DatabaseSeeder`): see `database/seeders/DatabaseSeeder.php`.
