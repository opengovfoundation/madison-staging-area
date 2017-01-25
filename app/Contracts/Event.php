<?php

namespace App\Contracts;

// TODO: what to do with this
interface Event
{
    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';

    public static function getName();
    public static function getType();
}
