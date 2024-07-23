Mello Core
---

## Technology:
- [Laravel](https://laravel.com/) - The PHP Framework
- [MySQL](https://www.mysql.com/) - Open source SQL database
- [MongoDB](https://www.mongodb.com/) - Open source NoSQL database
- [RabbitMQ](https://www.rabbitmq.com/) - Open source message broker

## Environment:
- [AUTH](https://gitlab.com/MelloInteractive/auth) - Auth service
- [MEDIA](https://gitlab.com/MelloInteractive/media) - Media service
- [LOG](https://gitlab.com/MelloInteractive/log) - Log service

## Initializing:
```sh
composer install
php artisan migrate
php artisan db:seed
php artisan test
```

## Faker seeding:
```sh
php artisan seed:faker
php artisan seed:faker-requests
```
