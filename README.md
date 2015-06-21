# git-webhooks

normalise webhook events for github, gitlab and bitbucket

Installation
------------

```bash
composer require davidbadura/git-webhooks
```

Example
-------

```php
use DavidBadura\GitWebhooks\EventFactory;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$factory = EventFactory::createDefault();

if ($event = $factory->create($request)) {
    // ...
}
```