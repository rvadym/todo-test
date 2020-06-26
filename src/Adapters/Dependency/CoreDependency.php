<?php declare(strict_types=1);

namespace ToDoTest\Adapters\Dependency;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Slim\Container as SlimContainer;

class CoreDependency extends AbstractDependency
{
    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    public function execute(): void
    {
        $this->addDoctrineDependency();
    }

    /**
     * @throws Exception\DependencyAlreadyBoundException
     */
    private function addDoctrineDependency(): void
    {
        $this->bind(EntityManager::class, function (SlimContainer $container): EntityManager
        {
            $config = Setup::createAnnotationMetadataConfiguration(
                $container['settings']['doctrine']['metadata_dirs'],
                $container['settings']['doctrine']['dev_mode']
            );

            $config->setMetadataDriverImpl(
                new AnnotationDriver(
                    new AnnotationReader,
                    $container['settings']['doctrine']['metadata_dirs']
                )
            );

            $config->setMetadataCacheImpl(
                new FilesystemCache(
                    $container['settings']['doctrine']['cache_dir']
                )
            );

            return EntityManager::create(
                $container['settings']['doctrine']['connection'],
                $config
            );
        });
    }
}
