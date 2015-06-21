<?php

namespace DavidBadura\GitWebhooks\Provider;

use DavidBadura\GitWebhooks\Event\AbstractEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
interface ProviderInterface
{
    /**
     * @param Request $request
     * @return AbstractEvent
     */
    public function create(Request $request);

    /**
     * @param Request $request
     * @return bool
     */
    public function support(Request $request);
}