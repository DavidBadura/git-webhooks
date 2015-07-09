<?php

namespace DavidBadura\GitWebhooks\Event;

use DavidBadura\GitWebhooks\Struct\Commit;
use DavidBadura\GitWebhooks\Struct\Repository;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PushEvent extends AbstractEvent
{
    const TYPE_BRANCH = 'branch';
    const TYPE_TAG    = 'tag';

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
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $branchName;

    /**
     * @var string
     */
    public $tagName;

    /**
     * @var Repository
     */
    public $repository;

    /**
     * @var Commit[]
     */
    public $commits = [];
}