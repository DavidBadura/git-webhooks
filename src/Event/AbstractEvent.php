<?php

namespace DavidBadura\GitWebhooks\Event;

use DavidBadura\GitWebhooks\Struct\Repository;
use DavidBadura\GitWebhooks\Struct\User;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractEvent
{
    /**
     * @var string
     */
    public $provider;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Repository
     */
    public $repository;
}