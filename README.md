# Symfony:

1. На своем сервере или виртуальной машине установить Symfony framework 5.2 и установить базу данных MySQL;
2. Создать таблицу с полями: Фамилия Имя Отчество и заполнить произвольными данными, не менее 1000 строк;
3. С помощью Phalcon создать REST Webservice c методами:
    
    3.1. Чтение записей из таблицы;
    
    3.2. Добавление записей в таблицу;
    
    3.3. Изменение записей в таблице;
    
    3.4. Удаление записей в таблице;
4. На отдельной странице вывести интерфейс для работы с таблицей;
5. Реализовать полнотекстовый поиск по данным из таблицы


# Установка
### Подключаем БД:
В файле .env, в корне проекта, добавляем строку подключения
DATABASE_URL="mysql://root:root@127.0.0.1:8889/restapi?serverVersion=5.6"

### Создаем таблицы в БД
В корне проекта выполняем:
php bin/console doctrine:migrations:migrate

# Сервисы:

### GET /api/install
Первоначальная установка, заполнение БД случайными данными
#### response:
- 200 - ok
- 404 - error

### GET /api/get/{id}
Получение данных о пользователе по его id
#### response:
- 200 - ok
```
[
    {
        "id" : 1,
        "firstname" : "TEST",
        "secondname" : "TEST",
        "surname" : "TEST"
    }
]
```
- 404 - error

### GET  /api/list/{id}/{limit}
Получение данных о пользователях начиная с id и лимитом в выдаче limit
#### response:
- 200 - ok
```
[
    {
        "id" : 1,
        "firstname" : "TEST",
        "secondname" : "TEST",
        "surname" : "TEST"
    }
]
```
- 404 - error

### GET /api/search/{find}/{id}/{limit}
Получение данных о пользователях по строке find с позиции id[>] и лимитом в выдаче limit
#### response:
- 200 - ok
```
[
    {
        "id" : 1,
        "firstname" : "TEST",
        "secondname" : "TEST",
        "surname" : "TEST"
    }
]
```
- 404 - error

### POST /api/post
Создание нового пользователя(ей), в ответ получение информации о пользователе с его id 
#### Body:
```
[
    {
        "firstname" : "TEST",
        "secondname" : "TEST",
        "surname" : "TEST"
    }
]
```
#### response:
- 201 - ok
```
[
    {
        "id" : 1,
        "firstname" : "TEST",
        "secondname" : "TEST",
        "surname" : "TEST"
    }
]
```
- 404 - error

### PUT /api/put
Изменение данных пользователя
#### Body:
```
[
    {
        "id" : 1,
        "firstname" : "TEST2",
        "secondname" : "TEST2",
        "surname" : "TEST2"
    }
]
```
#### response: 
- 201 - ok
- 404 - error

### DELETE /api/delete
Удаление пользователя
#### Body:
```
[
    {
        "id" : 1
    }
]
```
#### response: 
- 201 - ok
- 404 - error
