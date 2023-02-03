<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\ORMSetup;

$config = new PhpFile(__DIR__ . '/../migrations.php'); // Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders

$paths = [__DIR__ . '/../src/App/Entity'];
$isDevMode = true;

$ORMconfig = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection(['driver' => 'pdo_mysql', 'memory' => true], $ORMconfig);
$connectionLoader = new ExistingConnection($connection);

return DependencyFactory::fromConnection($config, $connectionLoader);

