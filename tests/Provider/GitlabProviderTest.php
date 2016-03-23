<?php

namespace DavidBadura\GitWebhooks\Provider;

use DavidBadura\GitWebhooks\Event\MergeRequestEvent;
use DavidBadura\GitWebhooks\Event\PushEvent;
use Symfony\Component\HttpFoundation\Request;

class GitlabProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testSupport()
    {
        $request = $this->createRequest('foo');

        $provider = new GitlabProvider();

        $this->assertTrue($provider->support($request));
    }

    public function testNoSupport()
    {
        $request = new Request();

        $provider = new GitlabProvider();

        $this->assertFalse($provider->support($request));
    }

    public function testPush()
    {
        $request = $this->createRequest('Push Hook', __DIR__ . '/_files/gitlab/push.json');

        $provider = new GitlabProvider();

        /** @var PushEvent $event */
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
        $this->assertEquals('refs/heads/master', $event->ref);
        $this->assertEquals('master', $event->branchName);
        $this->assertEquals(null, $event->tagName);
        $this->assertEquals('John Smith', $event->user->name);
        $this->assertEquals('Diaspora', $event->repository->name);
        $this->assertEquals('Mike', $event->repository->namespace);
        $this->assertCount(2, $event->commits);
    }

    public function testTag()
    {
        $request = $this->createRequest('Tag Push Hook', __DIR__ . '/_files/gitlab/tag.json');

        $provider = new GitlabProvider();

        /** @var PushEvent $event */
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
        $this->assertEquals('refs/tags/v1.0.0', $event->ref);
        $this->assertEquals(null, $event->branchName);
        $this->assertEquals('v1.0.0', $event->tagName);
        $this->assertEquals('Example', $event->repository->name);
        $this->assertEquals('Jsmith', $event->repository->namespace);
    }

    public function testMergeRequest()
    {
        $request = $this->createRequest('Merge Request Hook', __DIR__ . '/_files/gitlab/merge_request.json');

        $provider = new GitlabProvider();

        /** @var MergeRequestEvent $event */
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\MergeRequestEvent', $event);
        $this->assertEquals('opened', $event->state);
        $this->assertEquals('MS-Viewport', $event->title);
        $this->assertEquals('', $event->description);
        $this->assertEquals('master', $event->targetBranch);
        $this->assertEquals('ms-viewport', $event->sourceBranch);
        $this->assertEquals('Awesome Project', $event->repository->name);
        $this->assertEquals('Awesome Space', $event->repository->namespace);
        $this->assertEquals('Awesome Project', $event->sourceRepository->name);
        $this->assertEquals('Awesome Space', $event->sourceRepository->namespace);
        $this->assertEquals('da1560886d4f094c3e6c9ef40349f7d38b5d27d7', $event->lastCommit->id);
    }

    /**
     * @param string $event
     * @param string $file
     * @return Request
     */
    protected function createRequest($event, $file = null)
    {
        $content = null;

        if ($file) {
            $content = file_get_contents($file);
        }

        $request = new Request([], [], [], [], [], [], $content);
        $request->headers->set('X-Gitlab-Event', $event);

        return $request;
    }
}