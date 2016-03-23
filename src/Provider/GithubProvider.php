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

class GithubProvider extends AbstractProvider implements ProviderInterface
{
    const NAME = 'github';

    /**
     * @param Request $request
     * @return AbstractEvent
     */
    public function create(Request $request)
    {
        $data = $this->getData($request);
        switch ($request->headers->get('X-Github-Event')) {
            case 'push':
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
        return $request->headers->has('X-GitHub-Event');
    }

    /**
     * @param array $data
     * @return PushEvent
     */
    private function createPushEvent($data)
    {
        $event           = new PushEvent();
        $event->provider = self::NAME;
        $event->before   = $data['before'];
        $event->after    = $data['after'];
        $event->ref      = $data['ref'];

        $user       = new User();
        $user->id   = $data['sender']['id'];
        $user->name = $data['pusher']['name'];

        if (isset($data['pusher']['email'])) {
            $user->email = $data['pusher']['email'];
        }

        $repository              = new Repository();
        $repository->id          = $data['repository']['id'];
        $repository->name        = $data['repository']['name'];
        $repository->namespace   = $this->extractNamespace($data['repository']['full_name']);
        $repository->description = $data['repository']['description'];
        $repository->homepage    = $data['repository']['homepage'];
        $repository->url         = $data['repository']['html_url'];

        $event->user       = $user;
        $event->repository = $repository;
        $event->commits    = $this->createCommits($data['commits']);

        $event->type = Util::getPushType($event->ref);

        if ($event->type == PushEvent::TYPE_BRANCH) {
            $event->branchName = Util::getBranchName($event->ref);
        } else {
            $event->tagName = Util::getTagName($event->ref);
        }

        return $event;
    }

    /**
     * @param array $data
     * @return MergeRequestEvent
     */
    private function createMergeRequestEvent(array $data)
    {
        $event = new MergeRequestEvent();

        $event->provider    = self::NAME;
        $event->id          = $data['pull_request']['id'];
        $event->title       = $data['pull_request']['title'];
        $event->description = $data['pull_request']['body'];

        $event->targetBranch = $data['pull_request']['base']['ref'];
        $event->sourceBranch = $data['pull_request']['head']['ref'];
        $event->state        = $data['action'];
        $event->createdAt    = new \DateTime($data['pull_request']['created_at']);
        $event->updatedAt    = new \DateTime($data['pull_request']['updated_at']);

        $user       = new User();
        $user->id   = $data['pull_request']['user']['id'];
        $user->name = $data['pull_request']['user']['login'];

        $event->user             = $user;
        $event->repository       = $this->createRepository($data['pull_request']['base']['repo']);
        $event->sourceRepository = $this->createRepository($data['pull_request']['head']['repo']);

        // TODO request data from $data['pull_request']['commits_url']
        $event->lastCommit     = new Commit();
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

        $repository->id          = $data['id'];
        $repository->name        = $data['name'];
        $repository->description = $data['description'];
        $repository->namespace   = $this->extractNamespace($data['full_name']);
        $repository->url         = $data['ssh_url'];
        $repository->homepage    = $data['html_url'];

        return $repository;
    }

    /**
     * @param array $data
     * @return Commit
     */
    protected function createCommit(array $data)
    {
        $commit = new Commit();

        $commit->id      = $data['id'];
        $commit->message = $data['message'];
        $commit->date    = new \DateTime($data['timestamp']);

        $user        = new User();
        $user->name  = $data['author']['name'];
        $user->email = $data['author']['email'];

        $commit->author = $user;

        return $commit;
    }

    /**
     * @param string $fullName
     * @return string
     */
    private function extractNamespace($fullName)
    {
        return array_shift(explode('/', $fullName));
    }
}
