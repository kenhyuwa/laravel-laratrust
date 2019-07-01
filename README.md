![STARTER-PACK-LARATRUST](public/images/modern-skin-messenger.png?raw=true "STARTER-PACK-LARATRUST")

## STARTER-PACK-LARATRUST
Starter web project laravel base on [Modern AdminLTE](https://github.com/kenhyuwa/modern-adminlte)

![STARTER-PACK-LARATRUST](public/images/modern-skin-messenger.png?raw=true "STARTER-PACK-LARATRUST")

## Fiture
1. Authentication (login, register, reset password)
2. Yajra Datatable
3. Nestable menu

## Installation

Initial project

> Clone repository and create new repository for your project. Don't make this repository for your project. Because, this repository just starter project, not primary project application.

```bash 
foo@bar:~$ cp .env.example .env 
```

## Setup your connection

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=secret
DB_PASSWORD=secret
```

```bash
foo@bar:~$ composer install
foo@bar:~$ php artisan key:generate 
foo@bar:~$ git remote set-url origin https://github.com/USERNAME/REPOSITORY.git

## Setup application 

Fresh installation 

```bash
foo@bar:~$ php artisan laravelia:install
```

Regenerate user of application

```bash
foo@bar:~$ php artisan laravelia:user
```

Regenerate menu of application

```bash
foo@bar:~$ php artisan laravelia:menu
```

## Usage

Add class "modern-skin-messenger" into body

```html
<body class="hold-transition modern-skin-messenger sidebar-mini">
	...All content of page
</body>
```

for login/register page, add "modern-skin-messenger" and if you want background image on page, just add class "with-bg" into body.


```html
<body class="hold-transition login-page modern-skin-messenger">
	...All content of page
</body>
```
or
```html
<body class="hold-transition login-page modern-skin-messenger with-bg">
	...All content of page
</body>
```

## Skin available
1. dark 
2. green 
3. messenger 
4. orange 
5. purple
6. red

## Thanks to
Almsaeed Studio team & all library

## License
[MIT](LICENSE)