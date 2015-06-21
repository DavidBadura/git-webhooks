<?php

namespace DavidBadura\GitWebhooks\Event;

use DavidBadura\GitWebhooks\Struct\Commit;
use DavidBadura\GitWebhooks\Struct\Repository;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
abstract class AbstractGitEvent extends AbstractEvent
{
    /**
     * @var string
     */
    public $before;

    /**
     * @var string
     */
    public $after;

    /**
     * @var string
     */
    public $ref;

    /**
     * @var Repository
     */
    public $repository;

    /**
     * @var Commit[]
     */
    public $commits = [];
}