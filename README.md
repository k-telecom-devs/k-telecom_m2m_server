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
    }
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
post /api/version
    file_url:(Путь до файла с новой версией),
    description:(Описание версии),
    version:(Номер новой версии),
```
**Возвращает**
```
{
    "message": "Version created successfully."
}
```
<br>


