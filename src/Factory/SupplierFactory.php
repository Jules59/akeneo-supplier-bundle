<?php

namespace Cgi\SupplierBundle\Factory;

use Akeneo\Component\StorageUtils\Factory\SimpleFactoryInterface;
use Cgi\SupplierBundle\Entity\Supplier;
use Cgi\SupplierBundle\Event\SupplierEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class SupplierFactory
 *
 * @package Cgi\SupplierBundle\Factory
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierFactory implements SimpleFactoryInterface
{
    /** @var string */
    protected $supplierClass;
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * SupplierFactory constructor.
     *
     * @param string                   $supplierClass
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct($supplierClass, EventDispatcherInterface $eventDispatcher)
    {
        $this->supplierClass   = $supplierClass;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Create supplier.
     *
     * @return Supplier New empty supplier.
     */
    public function create()
    {
        // Instantiate supplier.
        $supplier = new $this->supplierClass();

        // Dispatch supplier creation event.
        $this->eventDispatcher->dispatch(
            SupplierEvents::CREATE,
            new GenericEvent($supplier)
        );

        return $supplier;
    }
}