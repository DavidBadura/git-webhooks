<?php

namespace DavidBadura\GitWebhooks\Event;

use DavidBadura\GitWebhooks\Struct\Commit;
use DavidBadura\GitWebhooks\Struct\Repository;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class MergeRequestEvent extends AbstractEvent
{
    const STATE_OPEN = 'opened'; // todo fix value
    const STATE_MERGED = 'merged';
    const STATE_CLOSED = 'closed';

    /**
     * @deprecated
     */
    const STATE_OPENED = self::STATE_OPEN;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var Repository
     */
    public $sourceRepository;

    /**
     * @var string
     */
    public $targetBranch;

    /**
     * @var string
     */
    public $sourceBranch;

    /**
     * @var string
     */
    public $state;

    /**
     * @var Commit
     */
    public $lastCommit;

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var \DateTime
     */
    public $updatedAt;
}