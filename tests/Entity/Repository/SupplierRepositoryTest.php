<?php

namespace Cgi\SupplierBundle\tests\Entity\Repository;

use Cgi\SupplierBundle\Entity\Supplier;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SupplierRepositoryTest
 *
 * @package Cgi\SupplierBundle\tests\Entity\Repository
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierRepositoryTest extends WebTestCase
{
    /**
     * Test user repository method to find supplier by user.
     *
     * @throws \Exception
     */
    public function testFindSuppliersByUser()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        // Retrieve admin user.
        $user = $kernel->getContainer()->get('pim_user.repository.user')->findOneByIdentifier('admin');
        // Retrieve or create supplier.
        /** @var Supplier $supplier */
        $supplier = $kernel->getContainer()->get('supplier.repository')->findOneByIdentifier('bosch');
        if (empty($supplier)) {
            $supplier = $kernel->getContainer()->get('supplier.factory')->create();
            $supplier->setCode('bosch');
            $supplier->setName('Bosch');
        }

        // Set user & save supplier in DB.
        $supplier->setUsers([$user]);
        $kernel->getContainer()->get('supplier.manager')->save($supplier);

        // Find supplier.
        $suppliers = $kernel->getContainer()->get('supplier.repository')->findSuppliersByUser($user);

        // Suppliers found should contain 1 element.
        self::assertEquals(1, count($suppliers));
        $supplier = $suppliers[0];
        // This element should be "bosch" supplier.
        self::assertEquals('bosch', $supplier->getCode());
    }
}
