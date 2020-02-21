<?php

namespace Application\Service;

use Application\Entity\Category;

/**
 * The CategoryManager service is responsible for adding new categories, updating existing
 */
class CategoryManager
{

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This method adds a new post.
     */
    public function addCategory($data)
    {
        // Create new Category entity.
        $category = new Category();
        $category->setName($data['name']);
        $category->setStatus($data['status']);
        $currentDate = date('Y-m-d H:i:s');
        $category->setCreatedAt($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($category);

        // Apply changes to database.
        $this->entityManager->flush();

        // Retrurn category
        return $category->toArray();
    }

    /**
     * This method allows to update data of a single post.
     */
    public function updateCategory($category, $data)
    {
        $category->setName($data['name']);
        $category->setStatus($data['status']);

        // Apply changes to database.
        $this->entityManager->flush();

        // Retrurn category
        return $category->toArray();
    }

    /**
     * Returns status as a string.
     */
    public function getCategoryStatusAsString($category)
    {
        switch ($category->getStatus()) {
            case Category::STATUS_ACTIVE: return 'Active';
            case Category::STATUS_INACTIVE: return 'Inactive';
        }

        return 'Unknown';
    }

    /**
     * Removes post and all associated comments.
     */
    public function removeCategory($category)
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

}
