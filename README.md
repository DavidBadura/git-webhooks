# git-webhooks

[![Build Status](https://travis-ci.org/DavidBadura/git-webhooks.svg?branch=master)](https://travis-ci.org/DavidBadura/git-webhooks)
[![Latest Stable Version](https://poser.pugx.org/davidbadura/git-webhooks/v/stable)](https://packagist.org/packages/davidbadura/git-webhooks)
[![Total Downloads](https://poser.pugx.org/davidbadura/git-webhooks/downloads)](https://packagist.org/packages/davidbadura/git-webhooks)
[![Latest Unstable Version](https://poser.pugx.org/davidbadura/git-webhooks/v/unstable)](https://packagist.org/packages/davidbadura/git-webhooks)
[![License](https://poser.pugx.org/davidbadura/git-webhooks/license)](https://packagist.org/packages/davidbadura/git-webhooks)

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