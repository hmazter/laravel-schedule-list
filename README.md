Laravel Schedule List
=====================

[![Build Status](https://github.com/hmazter/laravel-schedule-list/workflows/run-tests/badge.svg?branch=master)](https://github.com/hmazter/laravel-schedule-list/actions)
[![StyleCI](https://github.styleci.io/repos/40648674/shield?branch=master)](https://github.styleci.io/repos/40648674)

[![Latest Stable Version](https://poser.pugx.org/hmazter/laravel-schedule-list/v/stable)](https://packagist.org/packages/hmazter/laravel-schedule-list)
[![Latest Unstable Version](https://poser.pugx.org/hmazter/laravel-schedule-list/v/unstable)](https://packagist.org/packages/hmazter/laravel-schedule-list)
[![Total Downloads](https://poser.pugx.org/hmazter/laravel-schedule-list/downloads)](https://packagist.org/packages/hmazter/laravel-schedule-list)
[![License](https://poser.pugx.org/hmazter/laravel-schedule-list/license)](https://packagist.org/packages/hmazter/laravel-schedule-list)

Laravel 5.6+ package to add a artisan command to list all scheduled artisan commands. 
With schedule time (cron expression), the command to execute and the command description.


## Install

Require this package with composer using the following command:

```bash
composer require hmazter/laravel-schedule-list
```

**Note!** For Laravel version 5.5 and below use [0.2.0](https://github.com/hmazter/laravel-schedule-list/tree/v0.2.0#Install) tag.

## Usage

Usage from the command line to show all your scheduled artisan commands:

```bash
php artisan schedule:list
```

Outputs:
```
 +--------------+---------------------+--------------+-------------------------------+
 | expression   | next run at         | command      | description                   |
 +--------------+---------------------+--------------+-------------------------------+
 | 0 14 * * 3 * | 2017-08-16 14:00:00 | email:export | Export users to email service |
 +--------------+---------------------+--------------+-------------------------------+
```

### Crontab style output

Use `--cron` to show the output in the same style as it would go in a crontab file.

Outputs:
```
0 14 * * 3 * '/usr/local/bin/php' 'artisan' email:export > '/dev/null' 2>&1`
```

### Verbose output

Use `-vv` to show the full command, including php binary path and output path.

Outputs:
```
+--------------+---------------------+----------------------------------------------------------------+-------------------------------+
| expression   | next run at         | command                                                        | description                   |
+--------------+---------------------+----------------------------------------------------------------+-------------------------------+
| 0 14 * * 3 * | 2017-08-16 14:00:00 | '/usr/local/bin/php' 'artisan' email:export > '/dev/null' 2>&1 | Export users to email service |
+--------------+---------------------+----------------------------------------------------------------+-------------------------------+
```

Using `-vv` together with `--cron` does not change to output from normal `--cron` output.

### Programmatic use

For occasions when you need to access the list of scheduled events programmatically
`\Hmazter\LaravelScheduleList\ScheduleList::all` exists that will return all the scheduled events as an array of `ScheduleEvent`.

Inject the `ScheduleList` or resolve it from the Container and then call `all()` to get all scheduled events.
Usage of it can be seen in [ListScheduler::handle](src/Console/ListScheduler.php)

## Define PHP Binary Path

If you use custom PHP Binary paths or you are using `\Hmazter\LaravelScheduleList\ScheduleList::all` within the context of a web application and not through the console, you can publish the package config file and defining your own binary path:

```
php artisan vendor:publish --provider="Hmazter\LaravelScheduleList\ScheduleListServiceProvider" --tag="config"
```

For example `config/schedule-list.php`:
```
<?php

return [
    'remove_strings_from_command' => [
        "'".PHP_BINARY."'",
        "'artisan'",
    ],
];
```

## Known limitations

Laravel ships with some special scheduling functions ex `between`, `unlessBetween`, `when` and  `skip`
these are not handled right now in the schedule listing output.
They are evaluated at each execution of the schedule and does not define any expression that can be included in the table.
