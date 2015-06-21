<?php

namespace DavidBadura\GitWebhooks\Provider;

use DavidBadura\GitWebhooks\Event\AbstractEvent;
use DavidBadura\GitWebhooks\Event\IssueEvent;
use DavidBadura\GitWebhooks\Event\MergeRequestEvent;
use DavidBadura\GitWebhooks\Event\PushEvent;
use DavidBadura\GitWebhooks\Event\TagEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class GitlabProvider implements ProviderInterface
{
    /**
     * @param Request $request
     * @return AbstractEvent
     */
    public function create(Request $request)
    {
        switch ($request->headers->get('X-Gitlab-Event')) {
            case 'Push Hook':
                return $this->createPushEvent($request);
            case 'Tag Push Hook':
                return $this->createTagEvent($request);
            case 'Issue Hook':
                return $this->createIssueEvent($request);
            case 'Merge Request Hook':
                return $this->createMergeRequestEvent($request);
            default:
                return null;
        }
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function support(Request $request)
    {
        return $request->headers->has('X-Gitlab-Event');
    }

    /**
     * @param Request $request
     * @return IssueEvent
     */
    private function createIssueEvent(Request $request)
    {

    }

    /**
     * @param Request $request
     * @return MergeRequestEvent
     */
    private function createMergeRequestEvent(Request $request)
    {

    }

    /**
     * @param Request $request
     * @return PushEvent
     */
    private function createPushEvent(Request $request)
    {

    }

    /**
     * @param Request $request
     * @return TagEvent
     */
    private function createTagEvent(Request $request)
    {

    }
}