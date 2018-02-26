<?php

namespace Cgi\SupplierBundle\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\QueryBuilder;
use Oro\Bundle\DataGridBundle\Datasource\Orm\OrmDatasource;
use Oro\Bundle\DataGridBundle\Event\BuildAfter;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SupplierUserDatagridSubscriber
 *
 * @package Cgi\SupplierBundle\Subscribers
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierUserDatagridSubscriber implements EventSubscriberInterface
{
    /** Define supplier user ids parameter name. */
    const SUPPLIER_USER_IDS_PARAMETER = 'supplier_user_ids';

    /** @var RequestStack */
    protected $requestStack;

    /**
     * OrmRelationDatagridListener constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to.
     */
    public static function getSubscribedEvents()
    {
        return [
            'oro_datagrid.datgrid.build.after' => 'onBuildAfter'
        ];
    }

    /**
     * Add filters parameters to where clause.
     *
     * @param BuildAfter $event Event used to retrieve current datagrid.
     */
    public function onBuildAfter(BuildAfter $event)
    {
        $dataGrid = $event->getDatagrid();
        $dataSource = $dataGrid->getDatasource();
        // If data grid is not 'supplier-user' grid or data source is not an ORM source, do nothing.
        if ($dataGrid->getName() == 'supplier-user-grid' && $dataSource instanceof OrmDatasource) {
            /** @var QueryBuilder $query */
            $queryBuilder = $dataSource->getQueryBuilder();

            // Add parameter to query builder.
            $queryParameters = [
                self::SUPPLIER_USER_IDS_PARAMETER => $this->getSupplierUserIds(),
            ];
            $queryBuilder->setParameters($queryParameters);
        }
    }

    /**
     * Retrieve supplier user ids provided in request parameters.
     *
     * @return string|null Return found parameter or null.
     */
    protected function getSupplierUserIds()
    {
        $request = $this->requestStack->getMasterRequest();

        if (is_null($request)) {
            return null;
        }

        return $request->get(self::SUPPLIER_USER_IDS_PARAMETER);
    }
}