# Develop REST API with Lumen and JWT authentication

# Installation

1. Clone this repo

```
git clone https://github.com/k-telecom-devs/k-telecom_m2m_server.git
```

2. Install composer packages

```
cd %YOUR_APP%
$ composer update
```

3. Create and setup .env file

```
make a copy of .env.example

$ copy .env.example .env
$ php artisan key:generate

put database credentials in .env file

$ php artisan jwt:secret
```

4. Migrate and insert records

```
$ php artisan migrate
```
<br><br>

# **API Documentation**

# Запросы без токкена аутентификации

## **Регистрация пользователя.**
Внесение пользователя в базу данных
```
POST /api/register
    name:(имя нового пользователя),
    email:(Почта нового пользователя),
    password:(Пароль нового пользователя)

```
**Возвращает**

    {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6NTAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTY2NjE1OTcxOCwiZXhwIjoxNjY2MjA2NTE4LCJuYmYiOjE2NjYxNTk3MTgsImp0aSI6ImRyT09OS2RJTkRZSUQyZnEiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.bWdN2EsvLAdxMaYoZTNAO2jpjpUV1ryNLpR90zHtTIw",
    "token_type": "bearer",
    "expires_in": 46800 
    }
<br>

## **Аутентификация пользователя.**

Проверяет нахождение пользователя в базе данных и возвращает токкен аутентификации

```
POST /api/login 
    email:(Почта существующего пользователя),
    password:(Пароль существующего пользователя)

```
**Возвращает**
```
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6NTAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTY2NjE1OTcxOCwiZXhwIjoxNjY2MjA2NTE4LCJuYmYiOjE2NjYxNTk3MTgsImp0aSI6ImRyT09OS2RJTkRZSUQyZnEiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.bWdN2EsvLAdxMaYoZTNAO2jpjpUV1ryNLpR90zHtTIw",
    "token_type": "bearer",
    "expires_in": 46800 
    }
```
<br>

## **Обновить токен**

Обновляет токен авторизованного пользователя
```
POST /api/refresh 
```
**Возвращает**
```
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6NTAwMFwvYXBpXC9yZWdpc3RlciIsImlhdCI6MTY2NjE1OTcxOCwiZXhwIjoxNjY2MjA2NTE4LCJuYmYiOjE2NjYxNTk3MTgsImp0aSI6ImRyT09OS2RJTkRZSUQyZnEiLCJzdWIiOjIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.bWdN2EsvLAdxMaYoZTNAO2jpjpUV1ryNLpR90zHtTIw"
}
```
<br>

## **Отправить данные датчика.**
Добавляет данные value и mac на момент отправки запроса и обновляет данные uptime и charge у датчика с соответсвующим mac.
```
POST /api/data
    mac:(Мак адресс датчика, данные которого отправляются на сервер),
    value:(Значения датчика на момент отправки),
    uptime:(Время работы датчика),
    charge:(Уровень заряда батареи датчика)
```

**Возвращает**
```
{
    "message": "Data created successfully, sensor updated"
}
```
<br>

## **Получить настройки датчика.**
Сервер отправит настройки датчика, которому принадлежит мак адрес
```
GET /api/sensor-settings
    mac:(Мак адресс датчика),
```

**Возвращает**
```
[
    {
        "id": 1,
        "created_at": "2022-10-19T06:35:14.000000Z",
        "updated_at": "2022-10-19T06:35:14.000000Z",
        "name": "misha",
        "sleep": 30,
        "version_id": 1,
        "sensor_id": 1,
        "version": {
            "id": 1,
            "created_at": "2022-10-19T06:30:41.000000Z",
            "updated_at": "2022-10-19T06:30:41.000000Z",
            "file_url": "kakoy/to/url",
            "version": "2.2.8",
            "description": "test"
        }
    }
]
```
<br><br>

# Запросы с токкеном аутентификации

## **Получить данные датчиков пользователя**
Сервер отправит данные всех датчиков со всех станций, которые пренадлежат пользователю
```
GET /api/sensor
```

**Возвращает**
```
[
    {
        "id": 1,
        "created_at": "2022-10-19T06:35:14.000000Z",
        "updated_at": "2022-10-19T06:40:15.000000Z",
        "mac": "qwerty1",
        "uptime": 322,
        "charge": 100,
        "station_id": 1,
        "data": [
            {
                "id": 1,
                "created_at": "2022-10-19T06:40:15.000000Z",
                "updated_at": "2022-10-19T06:40:15.000000Z",
                "value": "36.6",
                "sensor_id": 1
            }
        ]
    },
    {
        "id": 2,
        "created_at": "2022-10-19T06:35:21.000000Z",
        "updated_at": "2022-10-19T06:40:33.000000Z",
        "mac": "qwerty2",
        "uptime": 1337,
        "charge": 100,
        "station_id": 2,
        "data": [
            {
                "id": 2,
                "created_at": "2022-10-19T06:40:33.000000Z",
                "updated_at": "2022-10-19T06:40:33.000000Z",
                "value": "69.0",
                "sensor_id": 2
            }
        ]
    }
]
```
<br>

## **Изменение профиля пользователя**
Изменение настроек пользователя в базе данных

```
POST /api/profile-change
    phone_number:(Новый номер телефона пользователя),
    notifications:(Включить/выключить уведомления(принимает 0/1)),
    auto_update:(Включить/выключить автообновления(принимает 0/1)),
    auto_pay:(Включить/выключить автоплатеж(принимает 0/1)),
    email:(Новая почта пользователя),
    name:(Новое имя пользователя),
```
**Возвращает**
```
{
    "message": "Data updated successfully"
}
```
<br>

## **Получение профиля пользователя**
Получение пользователя и его данных в базе данных по токену аутентификации

```
GET /api/profile-change
```
**Возвращает**
```
{"message":{"id":2,"name":"pohui","email":"kobani4@mail.ru","created_at":"2022-10-19T06:08:38.000000Z","updated_at":"2022-10-21T05:36:54.000000Z","phone_number":"89999999999","notifications":1,"auto_update":1,"auto_pay":1}}
```
<br>

## **Регистрация нового датчика**
Добавление датчика в базу данных

```
POST /api/sensor
    mac:(Мак адрес нового датчика),
    station_id:(id станции, к которой принадлежит датчик),
    name:(Название датчика),
    version_id:(id версии, на которой работает датчик)
```
**Возвращает**
```
{
    "message": "Sensor created successfully."
}
```
<br>

## **Изменить настройки датчика**
Изменение текущих настроек определенного датчика, принадлежащего пользователю 
```
post /api/sensor-settings
    sensor_id:(id датчика, которому принадлежат эти настройки),
    name:(Новое имя датчика),
    sleep:(Интервал работы датчика),
    version_id:(id версии, на которой будет работать датчик),
    notification_start_at:(Начало отправки уведомлений),
    notification_end_at:(Конец отправки уведомлений),
    station_id:(Станция, к которой будет привязан датчик)
```
**Возвращает**
```
{
    "message": "Data created successfully, sensor updated"
}
```
<br>

## **Регистрация новой станции**
Добавление станции пользователя в базу данных
```
POST /api/station
    name:(Название новой станции),
```
**Возвращает**
```
{
    "message": "Station created successfully."
}
```
<br>

## **Получить станции пользователя**
Получить все станции, которые доступны пользователю
```
GET /api/station
```
**Возвращает**
```
[
    {
        "id": 1,
        "created_at": "2022-10-19T05:16:13.000000Z",
        "updated_at": "2022-10-19T05:16:13.000000Z",
        "user_id": 1,
        "settings": {
            "id": 1,
            "created_at": "2022-10-19T05:16:13.000000Z",
            "updated_at": "2022-10-19T05:16:13.000000Z",
            "name": "Zaloopas",
            "station_id": 1
        }
    },
    {
        "id": 2,
        "created_at": "2022-10-23T06:50:22.000000Z",
        "updated_at": "2022-10-23T06:50:22.000000Z",
        "user_id": 1,
        "settings": {
            "id": 2,
            "created_at": "2022-10-23T06:50:22.000000Z",
            "updated_at": "2022-10-23T06:50:22.000000Z",
            "name": "Ebasosina",
            "station_id": 2
        }
<<<<<<< HEAD
=======
    }
>>>>>>> c48cb0e43f29cfc8c622558d3b9a52a4c51db8f5
]
```
<br>

## **Изменить настройки станции**
```
post /api/station-settings
    station_id:(Станция, которой принадлежат настройки),
    name:(Новое имя станции),
```
**Возвращает**
```
{
    "message": "Data created successfully, sensor updated"
}
```
<br>

## **Получить настройки станции**
Сервер отправит настройки станции, которой принадлежит указанный id
```
post /api/station-settings
    station_id:(Станция, которой принадлежат настройки),
```
**Возвращает**
```
[
    {
        "id": 1,
        "created_at": "2022-10-19T06:09:13.000000Z",
        "updated_at": "2022-10-19T10:08:03.000000Z",
        "name": "mishanya",
        "station_id": 1
    }
]
```
<br>

## **Отправить новую версию**
Добавить в базу данных информацию о новой версии программного обеспечения 
```
POST /api/device-type
    device_type:(Указать новый тип датчиков),
```
**Возвращает**
```
{
    "message": "Version created successfully."
}
```
<br>

## **Отправить новую версию**
Добавить в базу данных информацию о новой версии программного обеспечения 
```
POST /api/version
    file_url:(Путь до файла с новой версией),
    description:(Описание версии),
    version:(Номер новой версии),
    device_type_id:(id типа устройства, которому пренадлежит новая версия)
```
**Возвращает**
```
{
    "message": "Version created successfully."
}
```
<br>


