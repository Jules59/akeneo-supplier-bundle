<?php

namespace Cgi\SupplierBundle\tests\Entity;

use Cgi\SupplierBundle\Entity\Supplier;
use PHPUnit\Framework\TestCase;

/**
 * Class SupplierTest
 *
 * @package Cgi\SupplierBundle\tests\Entity
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierTest extends TestCase
{

    /**
     * Verify if __toString supplier function return supplier name.
     *
     * @throws \Exception
     */
    public function testGetLabelProperty()
    {
        $supplier = new Supplier();
        $supplier->setName('Bosch');

        self::assertEquals('Bosch', $supplier->__toString());
    }

    /**
     * Verify if supplier custom entity name is "supplier".
     *
     * @throws \Exception
     */
    public function testGetCustomEntityName()
    {
        $supplier = new Supplier();

        self::assertEquals('supplier', $supplier->getCustomEntityName());
    }
}
