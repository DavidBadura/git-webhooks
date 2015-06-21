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
        $data = $this->getData($request);

        switch ($request->headers->get('X-Gitlab-Event')) {
            case 'Push Hook':
                return $this->createPushEvent($data);
            case 'Tag Push Hook':
                return $this->createTagEvent($data);
            case 'Issue Hook':
                return $this->createIssueEvent($data);
            case 'Merge Request Hook':
                return $this->createMergeRequestEvent($data);
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
     * @param array $data
     * @return IssueEvent
     */
    private function createIssueEvent(array $data)
    {

    }

    /**
     * @param array $data
     * @return MergeRequestEvent
     */
    private function createMergeRequestEvent(array $data)
    {

    }

    /**
     * @param array $data
     * @return PushEvent
     */
    private function createPushEvent(array $data)
    {

    }

    /**
     * @param array $data
     * @return TagEvent
     */
    private function createTagEvent(array $data)
    {

    }

    /**
     * @param Request $request
     * @return array
     */
    public function getData(Request $request)
    {
        $body = $request->getContent();

        return json_decode($body, true);
    }
}