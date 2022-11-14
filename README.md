На базе Yii2 Basic

Разрабатывал на windows - OpenServer
php 8.1 mysql nginx 1.21

Инструкция рабочая, проверил два раза :)

Список реализованного функционала
https://disk.yandex.ru/d/jvayrho1T8RB4g

1. git clone https://github.com/svjatoslav-torn/utiptest.git или https://github.com/svjatoslav-torn/utiptest.git .
2. composer update
3. Создайте БД
4. Настройте подключение к БД в /config/db.php

5. php yii migrate - накатить миграции

6. php yii rbac - инициализация рол бэйс контрола

7. php yii seeder - создаст все в дефолтных количествах

Также у SeederController:
- php yii seeder/categories   ?int = 5 необязательный параметр количество записей
- php yii seeder/users   ?int = 2 необязательный параметр количество записей
- php yii seeder/posts   ?int = 20 необязательный параметр количество записей
- php yii seeder/comments ?int = 100 необязательный параметр количество записей

8. php yii create-user   -    будет создан пользователь с ролью User. Для проверки доступов

9. php yii create-user admin   -    будет создан пользователь с ролью Admin

Опционально: php yii roles/revoke - удалить роль. php yii roles/assign - добавить роль


http://hostname/auth/register  -  доступна регистрация обычного пользователя. Поля: string $name, string $email, string $password
http://hostname/auth/login  -  получение Bearer токена. Поля: string $email, string $password