# Pondasi

## What is Pondasi?

Pondasi give you solid ground to develop your application in CodeIgniter 4 Framework. Inside pondasi you will find:

- auth
- serverside datatable
- dashboard admin (AdminLTE3)
- site settings
- unique admin url
- maintenance mode

## Installation & updates

1. `composer install`
2. `php spark migrate`
3. `php spark migrate -n IonAuth`
4. `php spark db:seed InstallSeeder`

then `composer update` whenever
there is a new release.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `.env.examples` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important

**Please** don't expose your `.env` file in GitHub repositories or public. This will bring an unexpected consequences for your project.

## Dashboard Credential

```sh
Email: admin@admin.com
Password: password
```

## Server Requirements

PHP version 7.2 or higher is required, with the following extensions installed: 

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)
