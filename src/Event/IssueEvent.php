<?php

namespace DavidBadura\GitWebhooks\Event;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class IssueEvent extends AbstractEvent
{
    const ACTION_OPEN = 'open';
    const ACTION_CLOSE = 'close';

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
     * @var string
     */
    public $action;
}