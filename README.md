Laravel 5 Schedule List
=====================

[![Build Status](https://travis-ci.org/hmazter/laravel-schedule-list.svg?branch=master)](https://travis-ci.org/hmazter/laravel-schedule-list)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/hmazter/laravel-schedule-list/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/hmazter/laravel-schedule-list/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/hmazter/laravel-schedule-list/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/hmazter/laravel-schedule-list/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/hmazter/laravel-schedule-list/v/stable)](https://packagist.org/packages/hmazter/laravel-schedule-list)
[![Latest Unstable Version](https://poser.pugx.org/hmazter/laravel-schedule-list/v/unstable)](https://packagist.org/packages/hmazter/laravel-schedule-list)
[![Total Downloads](https://poser.pugx.org/hmazter/laravel-schedule-list/downloads)](https://packagist.org/packages/hmazter/laravel-schedule-list)
[![License](https://poser.pugx.org/hmazter/laravel-schedule-list/license)](https://packagist.org/packages/hmazter/laravel-schedule-list)

Laravel 5 package to add a artisan command to list all scheduled artisan commands. 
With schedule time, command and description.


## Install

Require this package with composer using the following command:

```bash
composer require hmazter/laravel-schedule-list
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
Hmazter\LaravelScheduleList\ScheduleListServiceProvider::class,
```

## Usage

Usage from the command line to show all your scheduled artisan commands:

```bash
php artisan schedule:list
```

Outputs:

     +-------------+---------------------------+-----------------------------------------------------------------+
     | expression  | command                   | description                                                     |
     +-------------+---------------------------+-----------------------------------------------------------------+
     | 0 6 * * 1 * | email:export              | Export users to MailChimp                                       |
     +-------------+---------------------------+-----------------------------------------------------------------+

### Crontab style output

Use `--cron` to show the output in the same style as it would go in a crontab file.

Outputs:

    0 6 * * 1 * /usr/bin/php5 "artisan" email:export > /home/user/dev/export-email.log 2>&1 &

### Verbose output

Use `-vv` to show the full command, including php binary path and output path.

Outputs:

    +-------------+------------------------------------------------------------------------------------+---------------------------------------+
    | expression  | command                                                                            | description                           |
    +-------------+------------------------------------------------------------------------------------+---------------------------------------+
    | 0 6 * * 1 * | /usr/bin/php5 "artisan" email:export > /home/user/dev/export-email.log 2>&1 &      | Export new users to MailChimp         |
    +-------------+------------------------------------------------------------------------------------+---------------------------------------+

Using `-vv` together with `--cron` does not change to output from normal `--cron` output.