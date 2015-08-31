<?php

namespace DavidBadura\GitWebhooks\Provider;

use Symfony\Component\HttpFoundation\Request;

class GithubProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testSupport()
    {
        $request = $this->createRequest('foo');

        $provider = new GithubProvider();

        $this->assertTrue($provider->support($request));
    }

    public function testNoSupport()
    {
        $request = new Request();

        $provider = new GithubProvider();

        $this->assertFalse($provider->support($request));
    }

    public function testPush()
    {
        $request = $this->createRequest('push', __DIR__ . '/_files/github/push.json');

        $provider = new GithubProvider();
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
    }

    public function testTag()
    {
        $request = $this->createRequest('push', __DIR__ . '/_files/github/tag.json');

        $provider = new GithubProvider();
        $event = $provider->create($request);

        $this->assertInstanceOf('DavidBadura\GitWebhooks\Event\PushEvent', $event);
    }

    public function testMergeRequest()
    {
        $request = $this->createRequest('Merge Request Hook', __DIR__ . '/_files/github/merge_request.json');

        $provider = new GithubProvider();
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
        $request->headers->set('X-Github-Event', $event);

        return $request;
    }
}
