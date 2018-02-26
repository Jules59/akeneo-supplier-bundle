<?php

namespace Cgi\SupplierBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pim\Bundle\CustomEntityBundle\Entity\AbstractCustomEntity;
use Pim\Bundle\UserBundle\Entity\UserInterface;

/**
 * Class Supplier
 *
 * @package Cgi\SupplierBundle\Entity
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class Supplier extends AbstractCustomEntity
{
    // Define reference data name.
    CONST REFERENCE_DATA_NAME = 'supplier';

    /** @var string */
    protected $name;
    /** @var UserInterface[] */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return UserInterface[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param UserInterface[] $users
     */
    public function setUsers(array $users)
    {
        $this->users = $users;
    }

    /**
     * Return property to use as label.
     */
    public static function getLabelProperty(): string
    {
        return 'name';
    }

    /**
     * Define custom entity name.
     *
     * @return string Custom entity name.
     */
    public function getCustomEntityName(): string
    {
        return self::REFERENCE_DATA_NAME;
    }
}