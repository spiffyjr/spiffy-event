# SpiffyEvent

[![Build Status](https://travis-ci.org/spiffyjr/spiffy-event.svg)](https://travis-ci.org/spiffyjr/spiffy-event)
[![Code Coverage](https://scrutinizer-ci.com/g/spiffyjr/spiffy-event/badges/coverage.png?s=271d4c5ee861f409fc110379e9bee04f333cadea)](https://scrutinizer-ci.com/g/spiffyjr/spiffy-event/)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/spiffyjr/spiffy-event/badges/quality-score.png?s=279062fbeb70ce48056990eb05d886db49d13c3d)](https://scrutinizer-ci.com/g/spiffyjr/spiffy-event/)

## Installation
Spiffy\Event can be installed using composer which will setup any autoloading for you.

`composer require spiffy/spiffy-event`

Additionally, you can download or clone the repository and setup your own autoloading.

## Create an event

```php
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
use Spiffy\Event\EventManager;

$em = new EventManager();

// Listen with a priority of 1
$em->on('foo', function() { echo 'a'; }, 1);

// Listen with a higher priority
$em->on('foo', function() { echo 'b'; }, 10);

// Event default with priority 0
$em->on('foo', function() { echo 'c'; });
$em->on('foo', function() { echo 'd'; });

// echos 'bacd'
```

## Firing events

```php
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
