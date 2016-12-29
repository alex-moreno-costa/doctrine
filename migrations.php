<?php

use Symfony\Component\Console;
use Doctrine\DBAL\Migrations\MigrationsVersion;
use Doctrine\DBAL\Migrations\Tools\Console\Command as MigrationsCommand;

require __DIR__ . '/vendor/autoload.php';

$cli = new Console\Application('Doctrine\Migrations', MigrationsVersion::VERSION());
$cli->setCatchExceptions(true);

$helperSet = require __DIR__ . '/cli-config.php';
$helperSet->set(new Console\Helper\QuestionHelper(), 'dialog');
$cli->setHelperSet($helperSet);

$commands = array();
$commands[] = new MigrationsCommand\ExecuteCommand();
$commands[] = new MigrationsCommand\GenerateCommand();
$commands[] = new MigrationsCommand\LatestCommand();
$commands[] = new MigrationsCommand\MigrateCommand();
$commands[] = new MigrationsCommand\StatusCommand();
$commands[] = new MigrationsCommand\VersionCommand();
$commands[] = new MigrationsCommand\DiffCommand();

$cli->addCommands($commands);
$cli->run();