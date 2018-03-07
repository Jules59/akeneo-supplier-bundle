<?php

namespace Cgi\SupplierBundle\Manager;

use Pim\Bundle\CatalogBundle\Doctrine\ORM\Repository\AttributeRepository;
use Pim\Component\Catalog\AttributeTypes;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\ReferenceData\ConfigurationRegistry;

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
    /** @var ConfigurationRegistry */
    protected $registry;
    /** @var string */
    protected $supplierClass;

    /**
     * AttributeManager constructor.
     *
     * @param AttributeRepository   $attributeRepository
     * @param ConfigurationRegistry $registry
     * @param string                $supplierClass
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ConfigurationRegistry $registry,
        $supplierClass
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->registry            = $registry;
        $this->supplierClass       = $supplierClass;
    }

    /**
     * Retrieve all supplier reference data attributes.
     *
     * @return AttributeInterface[] Supplier reference data attributes found.
     */
    public function getSupplierAttributes()
    {
        // Find all reference data attributes.
        $queryBuilder = $this->attributeRepository->createQueryBuilder('a');
        $queryBuilder->where(
            $queryBuilder->expr()->in(
                'a.type',
                [
                    AttributeTypes::REFERENCE_DATA_MULTI_SELECT,
                    AttributeTypes::REFERENCE_DATA_SIMPLE_SELECT
                ]
            )
        );

        // Filter on attributes based on reference data that use supplier.
        /** @var AttributeInterface[] $attributes */
        $attributes = $queryBuilder->getQuery()->getResult();
        $supplierAttributes = [];
        foreach ($attributes as $attribute) {
            if ($this->registry->get($attribute->getReferenceDataName())->getClass() == $this->supplierClass) {
                $supplierAttributes[] = $attribute;
            }
        }

        return $supplierAttributes;
    }
}