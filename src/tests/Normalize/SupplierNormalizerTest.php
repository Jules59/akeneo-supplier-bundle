<?php

namespace Cgi\SupplierBundle\tests\Normalize;

use Cgi\SupplierBundle\Normalizer\Normalization\Standard\SupplierNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SupplierNormalizerTest
 *
 * @package Cgi\SupplierBundle\tests\Normalize
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierNormalizerTest extends WebTestCase
{

    /**
     * Verify supplier normalization.
     *
     * @throws \Exception
     */
    public function testNormalize()
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $users = $kernel->getContainer()->get('pim_user.repository.user')->findBy(
            ['id' => ['1', '2']]
        );
        $supplier = $kernel->getContainer()->get('supplier.factory')->create();
        $supplier->setCode('bosch');
        $supplier->setName('Bosch');
        $supplier->setUsers($users);

        $normalizer = new SupplierNormalizer(['standard', 'internal_api']);
        $data = $normalizer->normalize($supplier, 'standard');

        self::assertEquals('bosch', $data['code']);
        self::assertEquals('Bosch', $data['name']);
        self::assertEquals([1,2], $data['userIds']);
    }

    /**
     * Verify if standard format are supported by normalizer.
     *
     * @throws \Exception
     */
    public function testSupportsNormalization()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        
        $normalizer = new SupplierNormalizer(['standard', 'internal_api']);
        $supplier = $kernel->getContainer()->get('supplier.factory')->create();
        
        self::assertEquals(true, $normalizer->supportsNormalization($supplier, 'standard'));
        self::assertEquals(true, $normalizer->supportsNormalization($supplier, 'internal_api'));
    }
}
