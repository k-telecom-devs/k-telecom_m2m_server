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

# API Documentation
