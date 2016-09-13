#!/usr/bin/env php
<?php

use Piccolo\DependencyInjection\Auryn\AurynDependencyInjectionContainer;
use Piccolo\SQL\Migrations\Application\DatabaseMigrationApplication;
use Piccolo\Web\WebApplication;

\date_default_timezone_set('UTC');

require_once (__DIR__ . '/../vendor/autoload.php');

$dic         = new AurynDependencyInjectionContainer();
$config      = require(__DIR__ . '/../config/config.php');

$application = new DatabaseMigrationApplication($dic, $config);
$application->execute();
