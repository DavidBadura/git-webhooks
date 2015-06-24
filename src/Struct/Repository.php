<?php

namespace DavidBadura\GitWebhooks\Struct;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class Repository
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $homepage;

    /**
     * @var string
     */
    public $url;
}