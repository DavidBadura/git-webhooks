<?php

namespace DavidBadura\GitWebhooks;

use DavidBadura\GitWebhooks\Event\AbstractEvent;
use DavidBadura\GitWebhooks\Provider\BitbucketProvider;
use DavidBadura\GitWebhooks\Provider\GitlabProvider;
use DavidBadura\GitWebhooks\Provider\GithubProvider;
use DavidBadura\GitWebhooks\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class EventFactory
{
    /**
     * @var ProviderInterface[]
     */
    protected $providers = [];

    /**
     * @param ProviderInterface[] $providers
     */
    public function __construct(array $providers = [])
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * @param Request $request
     * @return AbstractEvent
     */
    public function create(Request $request)
    {
        foreach ($this->providers as $provider) {
            if (!$provider->support($request)) {
                continue;
            }

            return $provider->create($request);
        }
    }

    /**
     * @param ProviderInterface $provider
     * @return $this
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * @return self
     */
    public static function createDefault()
    {
        return new self([
            new GitlabProvider(),
            new GithubProvider(),
            new BitbucketProvider()
        ]);
    }
}
