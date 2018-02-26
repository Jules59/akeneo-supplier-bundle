<?php

namespace Cgi\SupplierBundle\Updater;

use Akeneo\Component\StorageUtils\Exception\InvalidPropertyException;
use Akeneo\Component\StorageUtils\Exception\PropertyException;
use Akeneo\Component\StorageUtils\Updater\ObjectUpdaterInterface;
use Monolog\Logger;
use Pim\Bundle\UserBundle\Doctrine\ORM\Repository\UserRepository;
use Pim\Bundle\UserBundle\Entity\UserInterface;
use Cgi\SupplierBundle\Entity\Supplier;
use Cgi\SupplierBundle\Event\SupplierEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class SupplierUpdater
 *
 * @package Cgi\SupplierBundle\Updater
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierUpdater implements ObjectUpdaterInterface
{
    /** @var Logger */
    protected $logger;
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
    /** @var UserRepository */
    protected $userRepository;

    /**
     * SupplierUpdater constructor.
     *
     * @param Logger                   $logger
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserRepository           $userRepository
     */
    public function __construct(
        Logger $logger,
        EventDispatcherInterface $eventDispatcher,
        UserRepository $userRepository
    ) {
        $this->logger          = $logger;
        $this->eventDispatcher = $eventDispatcher;
        $this->userRepository  = $userRepository;
    }

    /**
     * Updates an object (erase the current data).
     *
     * @param Supplier $supplier The object to update.
     * @param array    $data     The data to update.
     * @param array    $options  The options to use.
     *
     * @return ObjectUpdaterInterface
     *
     * @throws PropertyException
     * @throws \TypeError
     */
    public function update($supplier, array $data, array $options = [])
    {
        // Dispatch pre update event.
        $this->eventDispatcher->dispatch(
            SupplierEvents::PRE_UPDATE,
            new GenericEvent($supplier)
        );

        // Set data to supplier.
        foreach ($data as $property => $value) {
            switch ($property) {
                case 'userIds':
                    $this->setUsersByIds($supplier, $value);
                    break;
                case 'users':
                    $this->setUsersByUserNames($supplier, $value);
                    break;
                default:
                    $this->setData($supplier, $property, $value);
            }
        }

        // Dispatch post update event.
        $this->eventDispatcher->dispatch(
            SupplierEvents::POST_UPDATE,
            new GenericEvent($supplier)
        );

        return $this;
    }

    /**
     * Set data to customer if it writable.
     *
     * @param Supplier $supplier Supplier to update.
     * @param string   $property Name of the property to update.
     * @param mixed    $value    Value to set.
     *
     * @throws \TypeError
     */
    protected function setData(Supplier $supplier, $property, $value)
    {
        $accessor = new PropertyAccessor();
        if (!$accessor->isWritable($supplier, $property)) {
            // A notice message is logged if the provided property is not writable.
            $supplierClass = get_class($supplier);
            $this->logger->addNotice(
                "Property {$property} is not writable for entity {$supplierClass}"
            );
            return;
        }

        // Set data.
        $accessor->setValue($supplier, $property, $value);
    }

    /**
     * Set users to supplier from user ids.
     *
     * @param Supplier     $supplier Supplier to update.
     * @param array|string $userIds  User ids to deal with.
     *
     * @throws InvalidPropertyException At least one of the provided ids is invalid.
     */
    protected function setUsersByIds($supplier, $userIds)
    {
        $supplier->setUsers($this->findUserBy('id', $userIds));
    }

    /**
     * Set users to supplier from user names.
     *
     * @param Supplier     $supplier  Supplier to update.
     * @param array|string $userNames User names to deal with.
     *
     * @throws InvalidPropertyException At least one of the provided ids is invalid.
     */
    protected function setUsersByUserNames($supplier, $userNames)
    {
        $supplier->setUsers($this->findUserBy('username', $userNames));
    }

    /**
     * Find users by provided property.
     *
     * @param string       $property Property used to find users.
     * @param string|array $data     Data used to search.
     *
     * @return UserInterface[]
     */
    protected function findUserBy($property, $data)
    {
        // Convert empty string to array.
        if (empty($data)) {
            $data = [];
        }

        // Transform provided user names string to array if it necessary.
        if (!is_array($data)) {
            $data = explode(',', $data);
        }

        // Retrieve users by id.
        $users = $this->userRepository->findBy([$property => $data]);

        // At least on of the ids is invalid.
        if (count($users) != count($data)) {
            throw new InvalidPropertyException(
                'users',
                implode(', ', $data),
                $this->userRepository->getClassName(),
                "At least, one of the provided values is invalid."
            );
        }

        return $users;
    }
}