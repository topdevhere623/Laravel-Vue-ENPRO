Установка проекта
1. Клонируем проект.
2. Создаем файл .env в котором (можно скопировать все из .env.example).
3. Собираем образ `docker-compose up -d`
4. Добавляем нужные папки **storage/framework/cache storage/framework/sessions storage/framework/views**
5. Запускаем composer `docker-compose exec app composer install`
6. Запускаем миграции `docker-compose exec app php artisan migrate --seed`
7. Запускаем `docker-compose exec app npm install`
8. Запускаем `docker-compose exec app npm run dev`
9. Переименовываем _.htaccess в .htaccess

в браузере http://localhost вводим логин и пароль из сида c паролями
