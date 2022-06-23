# CESI CUBE: Ressources Relationnelles

## Back end : Laravel

Dans .env:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=cesi-cube-db
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

Dans le terminal:
```
composer install
yarn install
```

lancer le docker compose, quand le conteneur est lancé,
dans le terminal:

```
php artisan migrate
php artisan db:seed
php artisan serve
```

