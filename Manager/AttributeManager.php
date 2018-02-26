<?php

namespace Cgi\SupplierBundle\Manager;

use Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository\AttributeRepository;
use Pim\Component\Catalog\Model\AttributeInterface;
use Cgi\SupplierBundle\Entity\Supplier;

/**
 * Class AttributeManager
 *
 * @package Cgi\SupplierBundle\Manager
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class AttributeManager
{
    /** @var AttributeRepository */
    protected $attributeRepository;

    /**
     * AttributeManager constructor.
     *
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Retrieve all supplier reference data attributes.
     *
     * @return AttributeInterface[] Supplier reference data attributes found.
     */
    public function getSupplierAttributes()
    {
        $queryBuilder = $this->attributeRepository->createQueryBuilder('a');
        $queryBuilder->where(
            $queryBuilder->expr()->like(
                'a.properties',
                $queryBuilder->expr()->literal('%"reference_data_name";s:8:"'. Supplier::REFERENCE_DATA_NAME . '"%')
            )
        );

        $query = $queryBuilder->getQuery();
        $query->useResultCache(true, 3600, "supplierAttributes");

        return $query->getResult();
    }
}