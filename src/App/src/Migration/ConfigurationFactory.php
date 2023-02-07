<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Psr\Container\ContainerInterface;
use RuntimeException;

final class ConfigurationFactory
{
    public function __invoke(ContainerInterface $container): Configuration
    {
        $directory = __DIR__ . DIRECTORY_SEPARATOR . '../../../../data/DoctrineORMModule/Migrations';

        if (!file_exists($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        $configuration = new Configuration();
        $configuration->addMigrationsDirectory('DoctrineORMModule\Migrations', $directory);
        $configuration->setAllOrNothing(true);
        $configuration->setMetadataStorageConfiguration(new TableMetadataStorageConfiguration());

        return $configuration;
    }
}
