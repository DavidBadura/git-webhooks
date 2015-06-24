<?php

namespace DavidBadura\GitWebhooks\Provider;

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
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
    }

    public function testTag()
    {
        $request = $this->createRequest('Tag Push Hook', __DIR__ . '/_files/gitlab/tag.json');

        $provider = new GitlabProvider();
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
    }

    public function testMergeRequest()
    {
        $request = $this->createRequest('Merge Request Hook', __DIR__ . '/_files/gitlab/merge_request.json');

        $provider = new GitlabProvider();
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\MergeRequestEvent', $event);
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