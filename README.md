# SpiffyEvent

[![Build Status](https://travis-ci.org/spiffyjr/spiffy-event.svg)](https://travis-ci.org/spiffyjr/spiffy-event)
[![Coverage Status](https://coveralls.io/repos/spiffyjr/spiffy-event/badge.png)](https://coveralls.io/r/spiffyjr/spiffy-event)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiffyjr/spiffy-event/badges/quality-score.png?s=b78ceecc07bd9aea4a0ef2f34683981d47ed352c)](https://scrutinizer-ci.com/g/spiffyjr/spiffy-event/)

## Create an event

```php
<?php

use Spiffy\Event\Event;

// Create an event that fires on 'foo'
$event = new Event('foo');

// Creates an event with a target
$event = new Event('foo', 'target');
$event->getTarget(); // 'target'

// Event can have parameters too
$event = new Event('foo', 'target', ['foo' => 'bar']);
$event->getParams()['foo']; // 'bar'
```

## Listening to events

```php
<?php

use Spiffy\Event\EventManager;

$em = new EventManager();

// Listen with a priority of 1
$em->on('foo', function() { echo 'a'; }, 1);

// Listen with a higher priority
$em->on('foo', function() { echo 'b'; }, 10);

// Event default with priority 0
$em->on('foo', function() { echo 'c'; });
$em->on('foo', function() { echo 'd'; });

// When fired, the result would be a SplQueue with 'bacd'
```

## Firing events

```php
<?php

use Spiffy\Event\Event;
use Spiffy\Event\EventManager;

$em = new EventManager();
$em->on('foo', function() { echo 'fired'; });

// Simplest form of fire requiring just the type
$em->fire('foo'); // fired

// You can also specify the target and params when using the type
$em->fire('foo', 'target', ['foo' => 'bar']);

// You can also provide your own event.
// This is identical to the fire above.
$event = new Event('foo', 'target', ['foo' => 'bar']);
$em->fire($event);
```

## Handling responses

```php
<?php

use Spiffy\Event\Event;
use Spiffy\Event\EventManager;

$em = new EventManager();

// Respones are returned as a SplQueue (FIFO).
$em->on('foo', function() { return 'a'; });
$em->on('foo', function() { return 'b'; });

// Outputs 'ab'
foreach ($em->fire('foo') as $response) {
    echo $response;
}