<?php

namespace DavidBadura\GitWebhooks\Provider;

use DavidBadura\GitWebhooks\Event\AbstractEvent;
use DavidBadura\GitWebhooks\Event\MergeRequestEvent;
use DavidBadura\GitWebhooks\Event\PushEvent;
use DavidBadura\GitWebhooks\Struct\Commit;
use DavidBadura\GitWebhooks\Struct\Repository;
use DavidBadura\GitWebhooks\Struct\User;
use DavidBadura\GitWebhooks\Util;
use Symfony\Component\HttpFoundation\Request;

class BitbucketProvider extends AbstractProvider implements ProviderInterface
{
    const NAME = 'bitbucket';

    /**
     * @param Request $request
     * @return AbstractEvent
     */
    public function create(Request $request)
    {
        $data = $this->getData($request);
        switch ($request->headers->get('X-Event-Key')) {
            case 'repo:push':
                return $this->createPushEvent($data);
            case 'pull_request':
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
        return $request->headers->has('X-Event-Key');
    }

    /**
     * @param array $data
     * @return PushEvent
     */
    private function createPushEvent($data)
    {
        $event = new PushEvent();
        $event->provider = self::NAME;

        $change = $data['push']['changes'][0];

        $event->before = $change['old']['target']['hash'];
        $event->after = $change['new']['target']['hash'];

        //$event->ref = $data['ref'];
        $event->type = $change['new']['type'];
        if ($event->type == PushEvent::TYPE_BRANCH) {
            $event->branchName = $change['new']['name'];
        } else {
            $event->tagName = $change['new']['name'];
        }

        $event->user = $this->createUser($data['actor']);
        $event->repository = $this->createRepository($data['repository']);
        //$event->commits = $this->createCommits($data['commits']);

        return $event;
    }

    /**
     * @param array $data
     * @return MergeRequestEvent
     */
    private function createMergeRequestEvent(array $data)
    {
        $event = new MergeRequestEvent();

        $event->provider = self::NAME;
        $event->id = $data['pull_request']['id'];
        $event->title = $data['pull_request']['title'];
        $event->description = $data['pull_request']['body'];

        $event->targetBranch = $data['pull_request']['base']['ref'];
        $event->sourceBranch = $data['pull_request']['head']['ref'];
        $event->state = $this->pullRequestState($data['pull_request']);
        $event->createdAt = new \DateTime($data['pull_request']['created_at']);
        $event->updatedAt = new \DateTime($data['pull_request']['updated_at']);

        $user = new User();
        $user->id = $data['pull_request']['user']['id'];
        $user->name = $data['pull_request']['user']['login'];

        $event->user = $user;
        $event->repository = $this->createRepository($data['pull_request']['base']['repo']);
        $event->sourceRepository = $this->createRepository($data['pull_request']['head']['repo']);

        // TODO request data from $data['pull_request']['commits_url']
        $event->lastCommit = new Commit();
        $event->lastCommit->id = $data['pull_request']['head']['sha'];

        return $event;
    }

    /**
     * @param array $data
     * @return Repository
     */
    private function createRepository(array $data)
    {
        $repository = new Repository();
        $repository->id = $data['uuid'];
        $repository->name = $data['name'];
        $repository->namespace = $this->extractNamespace($data['full_name']);
        $repository->description = null;
        $repository->homepage = $data['website'];
        $repository->url = null;

        return $repository;
    }

    /**
     * @param array $data
     * @return Commit
     */
    protected function createCommit(array $data)
    {
        $commit = new Commit();

        $commit->id = $data['id'];
        $commit->message = $data['message'];
        $commit->date = new \DateTime($data['timestamp']);

        $commit->author = $this->createUser($data['author']);

        return $commit;
    }

    /**
     * @param array $data
     * @return User
     */
    private function createUser(array $data)
    {
        $user = new User();
        $user->id = $data['uuid'];
        $user->name = $data['display_name'];

        return $user;
    }

    /**
     * @param string $fullName
     * @return string
     */
    private function extractNamespace($fullName)
    {
        $parts = explode('/', $fullName);

        return $parts[0];
    }

    /**
     * @param array $pullRequest
     * @return string
     */
    private function pullRequestState(array $pullRequest)
    {
        if ($pullRequest['state'] == 'open') {
            return MergeRequestEvent::STATE_OPEN;
        }

        if ($pullRequest['merged_at']) {
            return MergeRequestEvent::STATE_MERGED;
        }

        return MergeRequestEvent::STATE_CLOSED;
    }
}
