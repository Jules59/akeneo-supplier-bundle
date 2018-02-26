<?php

namespace Cgi\SupplierBundle\Entity\Repository;

use Pim\Bundle\CustomEntityBundle\Entity\Repository\CustomEntityRepository;
use Pim\Bundle\UserBundle\Entity\UserInterface;
use Cgi\SupplierBundle\Entity\Supplier;

/**
 * Class SupplierRepository
 *
 * @package Cgi\SupplierBundle\Entity\Repository
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierRepository extends CustomEntityRepository
{
    /**
     * Find all suppliers associated to provided user.
     *
     * @param UserInterface $user user used to find suppliers.
     *
     * @return Supplier[] Supplier associated to provided user as an array.
     */
    public function findSuppliersByUser(UserInterface $user)
    {
        $queryBuilder = $this->createQueryBuilder('supplier');
        $queryBuilder->where($queryBuilder->expr()->isMemberOf(':user', 'supplier.users'));
        $queryBuilder->setParameter('user', $user);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}