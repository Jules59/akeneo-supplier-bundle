<?php

namespace Cgi\SupplierBundle\tests\Factory;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


/**
 * Class SupplierFactoryTest
 *
 * @package Cgi\SupplierBundle\tests\Factory
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierFactoryTest extends WebTestCase
{
    /**
     * Test factory creation.
     *
     * @throws \Exception
     */
    public function testCreate()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $factory = $kernel->getContainer()->get('supplier.factory');
        $supplierClass = $kernel->getContainer()->getParameter('supplier.class.name');

        $supplier = $factory->create();

        self::assertEquals(get_class($supplier), $supplierClass);
    }
}
