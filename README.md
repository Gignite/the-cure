# The cure

A data mapper library with a minimal impact on your domain logic.

> Each time I play a song it seems more real.
>
> *Robert Smith*

This library came about from a number of previous interations
including [beautiful/domain](https://github.com/beautiful/domain)
and the frustrations of unit testing the active record pattern.

Written in PHP and must be used with 5.3 or later. The tests
require the Kohana 3.3 framework for autoloading classes and
configuration management. *Kohana is included as a submodule.*

## Taking the cure

Here's a quick teaser:

``` php
<?php
// We create a container which is responsible for creating
// mapper objects that connect with a mongo db
$container = new TheCure\Container('Mongo');

// We grab the user mapper
$mapper = $container->mapper('User');

// And the model
$user = $mapper->model();

// Update the model's name
$user->name('Luke');

// And persist it to mongo
$mapper->save($user);
?>
```

For more information on the cure start with "General Usage"
and work your way from there. The API is minimal so there is
not much to learn.

 - [General Usage](https://github.com/Gignite/the-cure/wiki/general-usage)
 - [Models and Mappers](https://github.com/Gignite/the-cure/wiki/models-and-mappers)
 - [Magic features](https://github.com/Gignite/the-cure/wiki/magic)
 - [Unit Testing](https://github.com/Gignite/the-cure/wiki/unit-testing)

## Some stats

 - 1655 non-commented lines of code
 - 27 classes with 124 methods (4 per class)
 - 7 interfaces
 - 682 statements (5 per method) with 100% covered
 - 124 tests and 161 asserts (in 1.20 seconds)

Run `rake test` to produce these stats yourself. We use a
`Rakefile` for producing stats from our unit tests so you will
need ruby and rake installed on your system.

[![Build Status](https://secure.travis-ci.org/Gignite/the-cure.png?branch=develop)](http://travis-ci.org/Gignite/the-cure)

## Unit tests

The cure is well covered with tests. We aim to:

 - test every individual unit in isolation via specification
   unit tests
 - maintain close to 100% test coverage, with 100% coverage
   during a release
 - test use cases via acceptance tests

### Running tests

To run everything in standard PHPUnit mode:

	phpunit

To run a particular area of logic:

	phpunit --group relationships

To run just the acceptance tests or specs:

	phpunit --group acceptance
	phpunit --group specs

To run a special spec report with code coverage:

	rake test

## License

MIT licensed.

## Authors

The Gignite Team and anyone willing to contribute.

### Gignite are hiring!

If you like working on open source projects like this as well
as the opportunity to build Gignite with us then get in touch!

[http://gun.io/careers/959/php-developer](http://gun.io/careers/959/php-developerb)