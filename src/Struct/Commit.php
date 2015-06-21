<?php

namespace DavidBadura\GitWebhooks\Struct;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class Commit
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $message;

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var User
     */
    public $author;
}