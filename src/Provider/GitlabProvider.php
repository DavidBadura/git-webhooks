<?php

namespace DavidBadura\GitWebhooks\Provider;

use DavidBadura\GitWebhooks\Event\AbstractEvent;
use DavidBadura\GitWebhooks\Event\IssueEvent;
use DavidBadura\GitWebhooks\Event\MergeRequestEvent;
use DavidBadura\GitWebhooks\Event\PushEvent;
use DavidBadura\GitWebhooks\Struct\Commit;
use DavidBadura\GitWebhooks\Struct\Project;
use DavidBadura\GitWebhooks\Struct\Repository;
use DavidBadura\GitWebhooks\Struct\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class GitlabProvider implements ProviderInterface
{
    const NAME = 'gitlab';

    /**
     * @param Request $request
     * @return AbstractEvent
     */
    public function create(Request $request)
    {
        $data = $this->getData($request);

        if (!$data) {
            return null;
        }

        switch ($request->headers->get('X-Gitlab-Event')) {
            case 'Push Hook':
            case 'Tag Push Hook':
                return $this->createPushEvent($data);
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
        $event           = new PushEvent();
        $event->provider = self::NAME;
        $event->before   = $data['before'];
        $event->after    = $data['after'];
        $event->ref      = $data['ref'];

        $user       = new User();
        $user->id   = $data['user_id'];
        $user->name = $data['user_name'];

        $project     = new Project();
        $project->id = $data['project_id'];

        $event->user       = $user;
        $event->project    = $project;
        $event->repository = $this->createRepository($data['repository']);
        $event->commits    = $this->createCommits($data['commits']);


        dump($data);
        dump($event);

        return $event;
    }

    /**
     * @param array $data
     * @return Repository
     */
    private function createRepository(array $data)
    {
        $repository              = new Repository();
        $repository->name        = $data['name'];
        $repository->url         = $data['url'];
        $repository->description = $data['description'];

        return $repository;
    }

    /**
     * @param array $data
     * @return Commit[]
     */
    private function createCommits(array $data)
    {
        $result = [];

        foreach ($data as $row) {
            $result[] = $this->createCommit($row);
        }

        return $result;
    }

    /**
     * @param array $data
     * @return Commit
     */
    private function createCommit(array $data)
    {
        $commit = new Commit();

        $commit->id      = $data['id'];
        $commit->message = $data['message'];
        $commit->date    = new \DateTime($data['timestamp']);

        $user       = new User();
        $user->name = $data['author']['name'];

        $commit->author = $user;

        return $commit;
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getData(Request $request)
    {
        $body = $request->getContent();

        return json_decode($body, true);
    }
}