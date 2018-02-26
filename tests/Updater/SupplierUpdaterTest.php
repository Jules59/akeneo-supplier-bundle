<?php

namespace Cgi\SupplierBundle\tests\Updater;

use Akeneo\Component\StorageUtils\Exception\InvalidPropertyException;
use Cgi\SupplierBundle\Entity\Supplier;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SupplierUpdaterTest
 *
 * @package Cgi\SupplierBundle\tests\Updater
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierUpdaterTest extends WebTestCase
{
    /**
     * Test supplier update with no issue.
     *
     * @throws \Exception
     */
    public function testSupplierRightUpdate()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $supplier = new Supplier();
        $updater  = $kernel->getContainer()->get('supplier.updater');
        $data     = [
            'code'      => 'bosch',
            'name'      => 'Bosch',
            'users'     => 'admin,julia',
            // Fake data is tested here because it should be ignore & is not consider as an error;
            'fake_data' => 'fake_data_value',
        ];
        $updater->update($supplier, $data);

        self::assertEquals('bosch', $supplier->getCode());
        self::assertEquals('Bosch', $supplier->getName());
        self::assertEquals(2, count($supplier->getUsers()));

        $data = [
            'code'    => 'bosch',
            'name'    => 'Bosch',
            'userIds' => '1,2,3'
        ];
        $updater->update($supplier, $data);

        self::assertEquals(3, count($supplier->getUsers()));

        $data = [
            'code'    => 'bosch',
            'name'    => 'Bosch',
            'userIds' => ''
        ];
        $updater->update($supplier, $data);

        self::assertEquals(0, count($supplier->getUsers()));
    }

    /**
     * Test supplier update with wrong user ids.
     *
     * @throws \Exception
     */
    public function testSupplierUpdateWithWrongUserIds()
    {
        $this->expectException(InvalidPropertyException::class);

        $kernel = static::createKernel();
        $kernel->boot();

        $supplier = new Supplier();
        $updater  = $kernel->getContainer()->get('supplier.updater');
        $data     = [
            'code'    => 'bosch',
            'name'    => 'Bosch',
            'userIds' => '1, 2, 3, fake_id'
        ];

        $updater->update($supplier, $data);
    }

    /**
     * Test supplier update with wrong user names.
     *
     * @throws \Exception
     */
    public function testSupplierUpdateWithWrongUserNames()
    {
        $this->expectException(InvalidPropertyException::class);

        $kernel = static::createKernel();
        $kernel->boot();

        $supplier = new Supplier();
        $updater  = $kernel->getContainer()->get('supplier.updater');
        $data     = [
            'code'  => 'bosch',
            'name'  => 'Bosch',
            'users' => 'admin, julia, fake_user_name'
        ];

        $updater->update($supplier, $data);
    }
}