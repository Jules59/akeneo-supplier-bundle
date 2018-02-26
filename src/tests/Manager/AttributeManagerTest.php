<?php

namespace Cgi\SupplierBundle\tests\Manager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AttributeManagerTest
 *
 * @package Cgi\SupplierBundle\tests\Manager
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class AttributeManagerTest extends WebTestCase
{
    /**
     * Test supplier attribute recuperation from DB.
     *
     * @throws \Exception
     */
    public function testGetSupplierAttributes()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $manager = $kernel->getContainer()->get('supplier.manager.attribute');

        $attributes = $manager->getSupplierAttributes();

        self::assertEquals(1, count($attributes));
        $attribute = $attributes[0];
        self::assertEquals('supplier', $attribute->getCode());
    }
}
