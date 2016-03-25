<?php

namespace DavidBadura\GitWebhooks\Provider;

use DavidBadura\GitWebhooks\Event\MergeRequestEvent;
use DavidBadura\GitWebhooks\Event\PushEvent;
use Symfony\Component\HttpFoundation\Request;

class BitbucketProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testSupport()
    {
        $request = $this->createRequest('foo');

        $provider = new BitbucketProvider();

        $this->assertTrue($provider->support($request));
    }

    public function testNoSupport()
    {
        $request = new Request();

        $provider = new BitbucketProvider();

        $this->assertFalse($provider->support($request));
    }

    public function testPush()
    {
        $request = $this->createRequest('repo:push', __DIR__ . '/_files/bitbucket/push.json');

        $provider = new BitbucketProvider();
        /** @var PushEvent $event */
        $event = $provider->create($request);

        dump($event);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
        $this->assertEquals('refs/heads/changes', $event->ref);
        $this->assertEquals('changes', $event->branchName);
        $this->assertEquals(null, $event->tagName);
        $this->assertEquals('baxterthehacker', $event->user->name);
        $this->assertEquals('public-repo', $event->repository->name);
        $this->assertEquals('baxterthehacker', $event->repository->namespace);
        $this->assertCount(1, $event->commits);
    }

    public function testTag()
    {
        return;

        $request = $this->createRequest('push', __DIR__ . '/_files/bitbucket/tag.json');

        $provider = new BitbucketProvider();
        /** @var PushEvent $event */
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
        $this->assertEquals('refs/tags/test-tag', $event->ref);
        $this->assertEquals(null, $event->branchName);
        $this->assertEquals('test-tag', $event->tagName);
        $this->assertEquals('public-repo', $event->repository->name);
        $this->assertEquals('baxterthehacker', $event->repository->namespace);
        $this->assertCount(1, $event->commits);
        $this->assertEquals('0d1a26e67d8f5eaf1f6ba5c57fc3c7d91ac0fd1c', $event->commits[0]->id);
    }

    public function testPullRequest()
    {
        return;

        $request = $this->createRequest('pull_request', __DIR__ . '/_files/bitbucket/pull_request.json');

        $provider = new BitbucketProvider();

        /** @var MergeRequestEvent $event */
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\MergeRequestEvent', $event);
        $this->assertEquals('opened', $event->state);
        $this->assertEquals('Update the README with new information', $event->title);
        $this->assertEquals('This is a pretty simple change that we need to pull into master.', $event->description);
        $this->assertEquals('master', $event->targetBranch);
        $this->assertEquals('changes', $event->sourceBranch);
        $this->assertEquals('public-repo', $event->repository->name);
        $this->assertEquals('baxterthehacker', $event->repository->namespace);
        $this->assertEquals('public-repo', $event->sourceRepository->name);
        $this->assertEquals('baxterthehacker', $event->sourceRepository->namespace);
        $this->assertEquals('0d1a26e67d8f5eaf1f6ba5c57fc3c7d91ac0fd1c', $event->lastCommit->id);
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
        $request->headers->set('X-Event-Key', $event);

        return $request;
    }
}