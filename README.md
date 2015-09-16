Laravel 5 Schedule List
=====================

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