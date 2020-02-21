<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\CategoryManager;
use Application\Controller\CategoryController;

/**
 * This is the factory for CategoryController. Its purpose is to instantiate the
 * controller.
 */
class CategoryControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoryManager = $container->get(CategoryManager::class);
        
        // Instantiate the controller and inject dependencies
        return new CategoryController($entityManager, $categoryManager);
    }
}


