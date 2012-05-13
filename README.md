# The cure

A data mapper library with a minimal impact on your domain
logic. In plainer terms, a library for writing the models of
your PHP application, mapping them to data stores such as
mongo db and unit testing against mock data stores.

This library came about from a number of previous interations
including [beautiful/domain](https://github.com/beautiful/domain)
and the frustrations of unit testing the active record pattern.

Written in PHP and must be used with 5.3 or later. The tests
require the Kohana 3.3 framework for autoloading classes and
configuration management. *Kohana is included as a submodule.*

## Taking the cure

So let's take a look at the medicine. In particular a model,
mapper and some usage of them.

### A model

``` php
<?php
namespace Models;
use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\HasMany;

class User extends \TheCure\Models\Magic {

	public static function attributes()
	{
		return new Attributes(
			new Field('name'),
			new HasMany('friends'));
	}

}
?>
```

### A mapper

``` php
<?php
namespace Mappers\Mongo;

class User extends \TheCure\Mappers\Mongo {}
?>
```

### Using the model and mapper

``` php
<?php
// We create a container which is responsible for creating
// mapper objects that connect with a mongo db
$container = new TheCure\Container('Mongo');

// We grab the user mapper
$mapper = $container->mapper('User');

// And a new model
$user = $mapper->model();

// Set the model's name
$user->name('Luke');

// And persist it to mongo
$mapper->save($user);

// Find the same model
$userCopy = $mapper->find_one(array('name' => 'Luke'));

// Note that these are the exact same object
var_dump($user === $userCopy);

// Create another user
$friend = $mapper->model();
$friend->name('Jake');

// Add Jake as a friend
$user->add_friends($friend);

// Get Luke's friends as a collection
foreach ($user->friends() as $_friend)
{
	echo "{$_friend->name()}\n";
}
?>
```

For more information on the cure start with "General Usage"
and work your way from there.

And relax! The API is minimal so there is not much to learn.

 - [General Usage](https://github.com/Gignite/the-cure/wiki/General-flow-of-using-the-cure)
 - [Models and Mappers](https://github.com/Gignite/the-cure/wiki/A-model-and-it's-mappers)
 - [Magic features](https://github.com/Gignite/the-cure/wiki/Magic-features)
 - [Unit Testing](https://github.com/Gignite/the-cure/wiki/Unit-testing)

## Some stats

 - 1655 non-commented lines of code
 - 27 classes with 124 methods (4 per class)
 - 7 interfaces
 - 682 statements (5 per method) with 100% covered
 - 124 tests and 161 asserts (in 1.20 seconds)

Run `rake test` to produce these stats yourself. We use a
`Rakefile` for producing stats from our unit tests so you will
need ruby and rake installed on your system.

## Unit tests

[![Build Status](https://secure.travis-ci.org/Gignite/the-cure.png?branch=develop)](http://travis-ci.org/Gignite/the-cure)

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