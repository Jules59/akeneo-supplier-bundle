<?php

namespace Cgi\SupplierBundle\Normalizer\Normalization\Standard;

use Cgi\SupplierBundle\Entity\Supplier;
use Cgi\SupplierBundle\Event\SupplierEvents;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class SupplierNormalizer
 *
 * @package Cgi\SupplierBundle\Normalizer\Standard
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierNormalizer implements NormalizerInterface
{
    /** @var array */
    protected $supportedFormat;

    /**
     * SupplierNormalizer constructor.
     *
     * @param array $supportedFormat
     */
    public function __construct($supportedFormat)
    {
        $this->supportedFormat = $supportedFormat;
    }

    /**
     * Normalizes a supplier into a set of arrays.
     *
     * @param Supplier $supplier Supplier to normalize
     * @param string   $format   Format the normalization result will be encoded as.
     * @param array    $context  Context options for the normalizer.
     *
     * @return array
     *
     * @throws InvalidArgumentException   Occurs when the object given is not an attempted type for the normalizer.
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it.
     * @throws LogicException             Occurs when the normalizer is not called in an expected context.
     */
    public function normalize($supplier, $format = null, array $context = []): array
    {
        return [
            'id'      => $supplier->getId(),
            'code'    => $supplier->getCode(),
            'name'    => $supplier->getName(),
            'userIds' => $this->getUserIds($supplier),
        ];
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed  $data   Data to normalize. It must be a supplier.
     * @param string $format The format being (de-)serialized from or into.
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Supplier && in_array($format, $this->supportedFormat);
    }

    /**
     * Format supplier user ids.
     *
     * @param Supplier $supplier
     *
     * @return array Supplier ids.
     */
    protected function getUserIds(Supplier $supplier)
    {
        $userIds = [];
        foreach ($supplier->getUsers() as $user) {
            $userIds[] = $user->getId();
        }

        return $userIds;
    }
}