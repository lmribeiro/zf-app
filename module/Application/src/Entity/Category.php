<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a category.
 * @ORM\Entity(repositoryClass="\Application\Repository\CategoryRepository")
 * @ORM\Table(name="category")
 */
class Category
{

    // Category status constants.
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="name")  
     */
    protected $name;

    /**
     * @ORM\Column(name="status")  
     */
    protected $status;

    /**
     * @ORM\Column(name="created_at")  
     */
    protected $createdAt;

    /**
     * Returns ID of this post.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets ID of this post.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns status.
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets status.
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns the date when this category was created.
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the date when this post was created.
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Return this object in array form.
     *
     * @return array
     */
    public function toArray()
    {
        $data = get_object_vars($this);

        foreach ($data as $attribute => $value) {
            if (is_object($value)) {
                $data[$attribute] = get_object_vars($value);
            }
        }

        return $data;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Fill this object from an array
     */
    public function exchangeArray($data)
    {
        if ($data != null) {
            foreach ($data as $attribute => $value) {
                if (!property_exists($this, $attribute)) {
                    continue;
                }
                $this->$attribute = $value;
            }
        }

        return $this;
    }

}
