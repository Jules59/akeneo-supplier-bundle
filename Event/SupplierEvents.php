<?php

namespace Cgi\SupplierBundle\Event;

/**
 * Class SupplierEvents
 *
 * @package Cgi\SupplierBundle\Event
 * @author  Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
class SupplierEvents
{
    const CREATE      = 'supplier.event.create';
    const PRE_UPDATE  = 'supplier.event.pre_update';
    const POST_UPDATE = 'supplier.event.post_update';
}