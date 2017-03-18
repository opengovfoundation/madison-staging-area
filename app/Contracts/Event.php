<?php

namespace App\Contracts;

// TODO: what to do with this
interface Event
{
    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';
    const TYPE_SPONSOR = 'sponsor';

    public static function getName();
    public static function getType();
}
