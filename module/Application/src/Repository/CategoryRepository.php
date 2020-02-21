<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Category;

/**
 * This is the custom repository class for Post entity.
 */
class CategoryRepository extends EntityRepository
{

    public function findCategories()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
                ->from(Category::class, 'c')
                ->orderBy('c.name', 'ASC');
        
        return $queryBuilder->getQuery();
    }

}
