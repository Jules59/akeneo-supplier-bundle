<?php

namespace Cgi\SupplierBundle\Subscribers;

use Oro\Bundle\DataGridBundle\Event\BuildAfter;
use Pim\Bundle\DataGridBundle\Datasource\ProductDatasource;
use Pim\Bundle\UserBundle\Context\UserContext;
use Pim\Component\Catalog\Query\Filter\Operators;
use Cgi\SupplierBundle\Entity\Repository\SupplierRepository;
use Cgi\SupplierBundle\Manager\AttributeManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ProductDatagridSubscriber
 *
 * @package Cgi\SupplierBundle\Subscribers
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class ProductDatagridSubscriber implements EventSubscriberInterface
{
    /** @var UserContext */
    protected $userContext;
    /** @var SupplierRepository */
    protected $supplierRepository;
    /** @var AttributeManager */
    protected $attributeManager;

    /**
     * ProductDatagridSubscriber constructor.
     *
     * @param UserContext        $userContext
     * @param SupplierRepository $supplierRepository
     * @param AttributeManager   $attributeManager
     */
    public function __construct(
        UserContext $userContext,
        SupplierRepository $supplierRepository,
        AttributeManager $attributeManager
    ) {
        $this->userContext        = $userContext;
        $this->supplierRepository = $supplierRepository;
        $this->attributeManager   = $attributeManager;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'oro_datagrid.datgrid.build.after.product-grid'           => 'filterOnSupplier',
            'oro_datagrid.datgrid.build.after.published-product-grid' => 'filterOnSupplier',
        ];
    }

    /**
     * Filter product to display only products associated with supplier associated to logged user.
     *
     * @param BuildAfter $event Event used to modify data grid request.
     */
    public function filterOnSupplier(BuildAfter $event)
    {
        // Retrieve all suppliers associated to logged user.
        $suppliers = $this->supplierRepository->findSuppliersByUser($this->userContext->getUser());
        // If user is not associated to any supplier then do nothing.
        if (empty($suppliers)) {
            return;
        }

        // Retrieve attribute associated with supplier reference data.
        $supplierAttributes = $this->attributeManager->getSupplierAttributes();
        if (empty($supplierAttributes)) {
            return;
        }

        // Extract supplier codes.
        $supplierCodes = [];
        foreach ($suppliers as $supplier) {
            $supplierCodes[] = $supplier->getCode();
        }
        // Add filter for each found attributes.
        /** @var ProductDatasource $dataSource */
        $dataSource = $event->getDatagrid()->getDatasource();
        foreach ($supplierAttributes as $supplierAttribute) {
            $dataSource->getProductQueryBuilder()->addFilter(
                $supplierAttribute->getCode(),
                Operators::IN_LIST,
                $supplierCodes
            );
        }
    }
}