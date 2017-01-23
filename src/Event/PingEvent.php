<?php

namespace DavidBadura\GitWebhooks\Event;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class PingEvent extends AbstractEvent
{
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