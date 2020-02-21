<?php

namespace Application\Controller\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\CategoryManager;
use Application\Controller\ApiController;

class ApiControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get(EntityManager::class);
        $categoryManager = $container->get(CategoryManager::class);

        return new ApiController($entityManager, $categoryManager);
    }
}
