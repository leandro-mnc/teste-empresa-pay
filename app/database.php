<?php

use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Container\ContainerInterface;
use App\Application\Settings\SettingsInterface;

return function (ContainerBuilder $container) {
    $container->addDefinitions([
        EntityManagerInterface::class => function(ContainerInterface $container) {
            /** @var SettingsInterface $settings */
            $settings = $container->get(SettingsInterface::class);

            /** @var array $doctrineSettings */
            $doctrineSettings = $settings->get('doctrine');
            
            // Use the ArrayAdapter or the FilesystemAdapter depending on the value of the 'dev_mode' setting
            // You can substitute the FilesystemAdapter for any other cache you prefer from the symfony/cache library
            $cache = $doctrineSettings['dev_mode'] ?
                new ArrayAdapter() :
                new FilesystemAdapter(directory: $doctrineSettings['cache_dir']);

            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: $doctrineSettings['metadata_dirs'],
                isDevMode: $doctrineSettings['dev_mode'],
                cache: $cache
            );

            $connection = DriverManager::getConnection($doctrineSettings['connection'], $config);

            return new EntityManager($connection, $config);
        },
        ConsoleRunner::class => function(ContainerInterface $container) {
            return ConsoleRunner::run(new SingleManagerProvider($container->get(EntityManager::class)));
        }
    ]);
};