<?php

namespace DavidBadura\GitWebhooks\Event;

use DavidBadura\GitWebhooks\Struct\Project;
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
     * @var Project
     */
    public $project;
}