# The cure

A data mapper library with a minimal impact on your domain
logic. In plainer terms, a library for writing the models of
your PHP application, mapping them to data stores such as
MongoDB and unit testing against mock data stores.

This library came about from a number of previous interations
including [beautiful/domain](https://github.com/beautiful/domain)
and the frustrations of unit testing the active record pattern.

Written in PHP and must be used with 5.3 or later. The tests
require the Kohana 3.3 framework for autoloading classes and
configuration management. *Kohana is included as a submodule.*

## Installation

The cure is available as a [composer][composer] package so you
can add it as a dependency.

[composer]: http://getcomposer.com

``` json
{
	"require": {
		"php": ">=5.3.2",
		"the-cure/the-cure": ">=0.2.1"
	}
}
```

Alternatively you can download the latest zip from github:

[The cure v0.2.1](https://github.com/Gignite/the-cure/zipball/v0.2.1)

## Ingredients

Before we go into that CRUD stuff let's explain a few
ingredients of the cure.

### Models

In the cure we use a class known as a model to describe domain
logic. Your models in your application will represent users,
blog posts, spaceships or anything that concerns yours or your
bosses business. 

The cure provides two base models. Here is an example of the
second, more ORM like model, `TheCure\Models\Magic`:

``` php
<?php
namespace Models;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

use TheCure\Relationships\HasManyRelationship;

class User extends \TheCure\Models\MagicModel {

	public static function attributes()
	{
		return new AttributeList(
			new Field('name'),
			new HasManyRelationship(
				'friends',
				array('mapperSuffix' => 'User')));
	}

}
?>
```

The magical ORM features of the class `Models\User` extends
include providing a getter/setter method for each field along
with several more per relationship. The example above includes
a `TheCure\Relationships\HasMany` which adds methods for
finding, adding and removing other `User` models from it's
`friends` collection.

### Mappers

The second piece to the puzzle are mappers. These classes
describe the behaviour for persisting your models to
databases, flat files, JSON, S3, or whatever. Currently the
cure provides two mappers, one for MongoDB, the other a mock
mapper for your unit tests.

Because `TheCure\Mappers\Mongo` contains enough behaviour to
get us going we can simply extend this class and leave it
blank for our example below.

``` php
<?php
namespace Mappers\Mongo;

class User extends \TheCure\Mappers\MongoMapper {}
?>
```

## Instructions

Now that we've assembled some ingredients let's go over the
instructions for using them.

We create a container which is responsible for creating mapper
objects that connect with a MongoDB.

``` php
<?php
$container = new TheCure\Container('Mongo');
?>
```

We grab the user mapper, this will be used for creating a new
user model as well as storing it later.

``` php
<?php
$mapper = $container->mapper('User'); // => Mappers\Mongo\User
?>
```

And a new user model to represent a single person.

``` php
<?php
$user = $mapper->model(); // => Models\User
?>
```

Set the model's name to "Luke", because it's a good name.

``` php
<?php
$user->name('Luke');
?>
```

And persist it to MongoDB. As you can see the API is fairly
minimal and quite self explanatory.

``` php
<?php
$mapper->save($user);
?>
```

Find the same model using `::find_one()`.

``` php
<?php
$userCopy = $mapper->findOne(array('name' => 'Luke'));

// Note that these are the exact same object
var_dump($user === $userCopy);
?>
```

Create another user with a name of "Jake".

``` php
<?php
$friend = $mapper->model();
$friend->name('Jake');
?>
```

Add Jake as a friend of Luke's.

``` php
<?php
$user->addFriends($friend);
?>
```

Get Luke's friends as a collection.

``` php
<?php
foreach ($user->friends() as $_friend)
{
	echo "{$_friend->name()}\n";
}
?>
```

For more information on the cure start with "General usage"
and work your way from there.

And relax! The API is minimal so there is not much to learn.

 - [General usage](https://github.com/Gignite/the-cure/wiki/General-flow-of-using-the-cure)
 - [A model and it's mappers](https://github.com/Gignite/the-cure/wiki/A-model-and-it's-mappers)
 - [Magic features](https://github.com/Gignite/the-cure/wiki/Magic-features)
 - [Unit testing your domain logic](https://github.com/Gignite/the-cure/wiki/Unit-testing-your-domain-logic)

## Statistics

 - 1717 non-commented lines of code
 - 28 classes with 127 methods (4 per class)
 - 7 interfaces
 - 705 statements (5 per method) with 100% covered
 - 127 tests and 165 asserts (in 0.97 seconds)

Run `rake test` to produce these stats yourself. We use a
`Rakefile` for producing stats from our unit tests so you will
need ruby and rake installed on your system.

## Testing

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
