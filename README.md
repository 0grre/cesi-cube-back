# CESI CUBE: Ressources Relationnelles

Le projet « (RE)Sources Relationnelles est une simulation d’un projet qui pourrait être porté par le Ministère des
Solidarités et de la Santé à destination des citoyens afin de proposer une plateforme de sources, ressources, et
d’échanges.
-----------
## Back end : Laravel

Pour la base de données:

```
$ docker-compose up -d
```

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
$ composer install
$ yarn install
```
-----------

Pour Laravel, dans le terminal:

```
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed
$ php artisan storage:link
$ php artisan serve
```
-----------

Créez un compte Algolia, créer uen nouvelle application.

Pour scout, dans .env:

```
SCOUT_DRIVER=algolia
SCOUT_QUEUE=true
SCOUT_IDENTIFY=true
ALGOLIA_APP_ID={your_algolia_app_id}
ALGOLIA_SECRET={your_write_algolia_api_key}
```

Dans le terminal:
```
$ php artisan scout:import
```
-----------

Pour les mails, juste à modifier le .env avec le bon smtp, 
juste suivre la doc du mailer utilisé
