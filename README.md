# The cure

A data mapper library with a minimal impact on your domain
logic. Low coupling and use of flexible interfaces make this
convention over configuration library completely customisable.

> Each time I play a song it seems more real.
>
> *Robert Smith*

This library came about from a number of previous interations
including [beautiful/domain](https://github.com/beautiful/domain)
and the frustrations of unit testing the active record pattern.

Written in PHP and must be used with 5.3 or later. The tests
require the Kohana 3.3 framework for autoloading classes and
configuration management. *Kohana is included as a submodule.*

## Some stats

 - 1655 non-commented lines of code
 - 27 classes with 124 methods (4 per class)
 - 7 interfaces
 - 682 statements with 100% covered (5 per method)
 - 124 tests and 161 asserts (in 1.19 seconds)

Run `rake test` to produce these stats yourself. We use a
`Rakefile` for producing stats from our unit tests so you will
need ruby and rake installed on your system.

[![Build Status](https://secure.travis-ci.org/Gignite/the-cure.png?branch=develop)](http://travis-ci.org/Gignite/the-cure)

## General flow of using the cure

 - Create a `TheCure\Container` that is the DI
   container for all mappers and the objects they create
 - Get a mapper object from `Container` using `::mapper()`

``` php
<?php
use TheCure\Container;
$container = new Container('Mongo');
$container->mapper('Profile'); // => Mappers\Mongo\Profile
$container->mapper('Media');   // => Mappers\Mongo\Media
?>
```

### Creating

 - Create a new model using `TheCure\Mappers\Mapper::model()`
 - Validate the model
 - Pass the model to `TheCure\Mappers\Mapper::save()`

``` php
<?php
$image = $container->mapper('Media')->model('Image');

if ($image->create($owner, compact('filename')))
{
	$container->mapper('Media')->save($image);
}
?>
```

### Updating

 - Use `TheCure\Mappers\Mapper::find_one()`
 - Operate on the model
 - Validate the model
 - Pass the model to `TheCure\Mappers\Mapper::save()`

``` php
<?php
$image = $container->mapper('Media')->find_one($id, 'Image'); // => Models\Media\Image
$image->caption('A new caption.');

if ($image->validation()->check())
{
	$container->mapper('Media')->save($image);
}
?>
```

### Finding

 - Use `TheCure\Mappers\Mapper::find_one()` to find a
   single `Model`
 - Use `TheCure\Mappers\Mapper::find()` to find a
   `Collection` of models

The update example includes a `::find_one()` call which has
two arguments. Optionally you can simply pass in an ID like
so:

``` php
<?php
$container->mapper('Profile')->find_one(); // => Models\Profile
$container->mapper('Profile')->find_one($id); // => Models\Profile

$container->mapper('Profile')->find_one($id, 'Artist');
// => Models\Profile\Artist

$container->mapper('Profile')->find_one(NULL, 'Artist');
// => Models\Profile\Artist

$container->mapper('Profile')->find_one(array('name' => 'Luke'), 'Artist');
// => Models\Profile\Artist
?>
```

`::find()` has a similar API to `::find_one()` the differences
being all of `::find()`s arguments are optional, and the
`$where` argument must be an array. If you supply no arguments
you will get a `Collection` representing an entire collection,
table, etc.

``` php
<?php
$container->mapper('Profile')->find();
// => TheCure\Collections\Model of Models\Profile

$container->mapper('Profile')->find(NULL, 'Artist');
// => TheCure\Collections\Model of Models\Profile\Artist

$container->mapper('Profile')->find(array('plan' => 'free'));
// => TheCure\Collections\Model of Models\Profile

$container->mapper('Profile')->find(array('plan' => 'free'), 'Artist');
// => TheCure\Collections\Model of Models\Profile\Artist
?>
```

### Working with collections

A `TheCure\Collections\Collection` is a container,
cursor and iterable that represents more than one document,
row, etc. It does not initialise any models however until that
row is iterated over, at that point an `IdentityMap` is used
to ensure the model isn't already in the ecosystem.

``` php
<?php
$collection = $mapper->mapper('Profile')->find();

foreach ($collection as $_model)
{
	echo "{$_model->name()}\n";
}
?>
```

### Deleting

 - Use `TheCure\Mappers\Mapper::delete()` to delete a
   model, collection or by a new query.

``` php
<?php
$profile_mapper = $container->mapper('Profile');

// Using a Model
$profile_mapper->delete($profile_mapper->find_one($id));

// Using a Collection
$profile_mapper->delete($profile_mapper->find());

// Using an ID
$profile_mapper->delete($id);

// Using a query (unlimited)
$profile_mapper->delete(array('type' => 'artist'));
?>
```

## A model and it's mappers

This is by convention only and it is completely possible to
not follow it.

A Model describes the business (or domain) logic for a
particular area of expertise. For example a Model might
describe a user of an application. This would likely be called
`Models\User`. The model would have methods for registering an
account, updating it's email address, etc.

In order to store `Models\User` in a Mongo database we need a
mapper. A mapper knows how to store a model's data into what
ever medium it wants. A mongo mapper for a user model would,
by convention, be called `Mappers\Mongo\User`.

If you wanted to test `Models\User` you would also need another
mapper. This mapper would be a mock and would be used to test
the data before and after an operation on your model. By
convention this would then be called `Mappers\Mock\User`.

This convention explains the API choice of
`Container::__construct()`, `Container::mapper()`,
`Mapper::find()` and `Mapper::find_one()`.

### TheCure\Container::__construct($type)

The argument passed here is added onto the `Mappers\` prefix,
so for example `new Container('Mongo')` creates a prefix
for all mappers called `Mappers\Mongo\`.

### TheCure\Container::mapper($mapper)

The argument passed to ::mapper() indicates the domain area
being mapped so for example `$container->mapper('Profile')`
will instantiate a single `Mappers\Mongo\Profile` and register
it with the container so that subsequent calls return the same
instance.

### TheCure\Mappers\Mapper::find($where, $suffix) and ::find_one($where, $suffix)

The second argument passed to `::find_one()` is a suffix for
the model class name which is by default a derivative of the
mapper class name. Take this example:

``` php
<?php
use TheCure\Container;
$container = new Container('Mongo');

// Using Mappers\Mongo\Profile to find Models\Profile\Artist
$model = $container->mapper('Profile')->find_one($id, 'Artist');

// Using Mappers\Mongo\Profile to find Models\Profile
$model = $container->mapper('Profile')->find_one($id);
?>
```

## Magic features

Usually I try to avoid magic code but expressing fields and
relationships is repetitive and I believe some so-called magic
is acceptable in specialised areas.

### Magic model

``` php
<?php
namespace Models;

use TheCure\Models\Magic as MagicModel;
use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\HasMany;
use TheCure\Container;

class User extends MagicModel {

	public static function attributes()
	{
		return new Attributes(
			new Field('name'),
			new HasMany('friends', array(
				'mapper_suffix' => 'User',
			)));
	}

}

$container = new Container('Mock');
// $container = new Container('Mongo');

// Create two new Models\User\Magic
$user = $container->mapper('User')->model('Magic');
$user->name($expectedName = 'Luke');
$this->assertSame($expectedName, $user->name());

$bob = $container->mapper('User')->model('Magic');
$bob->name($expectedFriendName = 'Bob');
$this->assertSame($expectedFriendName, $bob->name());

// Add a friend
$user->add_friends($bob);
$this->assertSame(1, $user->friends()->count());

// Remove a friend
$user->remove_friends($bob);
$this->assertSame(0, $user->friends()->count());
?>
```

### Attributes

To describe the various fields and relationships of a Magic
model we contain one or more `TheCure\Field` and
`TheCure\Relationships\Relationship` in a
`TheCure\Attributes` object.

#### Defining attributes

``` php
<?php
use TheCure\Models\Magic as MagicModel;
use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\HasOne;

class Car extends MagicModel {

	public static function attributes()
	{
		return new Attributes(
			new Field('license'),
			new HasOne('manufacturer', array(
				'mapper_suffix' => 'Manufacturer',
			)),
			new Field('model'),
			new Field('colour'),
			new HasOne('owner', array(
				'mapper_suffix' => 'Person',
			)));
	}

}
?>
```

In the example above we create a `Car` object which had a
number of relationships and fields. Both `Field` and
the `HasOne` relationship extend the same abstract object
`TheCure\Attribute\Attribute`. `Attribute` objects
passed into the `Attributes` constructor as inidividual params
or as an array are the added to the `Attributes` object.

The magic model then checks against these fields and
relationships every time it's magic `::__call()` method is
triggered. This is how magic getter, setter and deleter (??)
method are added.

#### Adding attributes

You may wish to modify the attributes of a sub class to the
magic model. `Attributes` has a number of methods for adding,
replacing and removing fields and relationships.

``` php
<?php
class LargeCar extends Car {

	public static function attributes()
	{
		$attributes = parent::attributes();
		$attributes->add(
			new Field('four_by_four', array('value' => FALSE)));
		return $attributes;
	}

}
?>
```

#### Modifying attributes

Here we added an additional field. If you tried adding a field
with a name already used you will get an
`Attribute\AliasTakenException` thrown. In order to avoid this
exception you must explicitly replace a field like so:

``` php
<?php
class HexColourCar extends Car {

	public static function attributes()
	{
		$attributes = parent::attributes();
		$attributes->replace(
			new Field('colour', array(
				'rules' => array('ValidColour::hex'),
			)));
		return $attributes;
	}

}
?>
```

We replaced the `colour` field with a similar field that has
additional validation.

#### Removing attributes

You may also wish to remove attributes from your model. You
may have guessed already but this is via the `::remove()`
method. Check it out:

``` php
<?php
class UnlicensedCar extends Car {

	public static function attributes()
	{
		$attributes = parent::attributes();
		$attributes->remove('license');
		return $attributes;
	}

}
?>
```

### Object, a data transfer object

In order to transfer data between models and mappers we have
devised a DTO called `Object`.

#### Creating an Object

`Object::__construct()` optionally takes two arguments. The
first is an array of data. The second a whitelist of keys to
extract from the first arg.

``` php
<?php
$object = new Object(array('name' => 'Luke'));

// Or filter your data
$object = new Object($_POST, array('name'));
?>
```

#### Updating an Object

``` php
<?php
$object->name = 'Jim';
$object->set('name', 'Jim');
$object->set(array(
	'name' => 'Jim',
	'age'  => 26,
));
$object->accessor('name', 'Jim');
?>
```

#### Getting data from an Object

``` php
<?php
var_dump($object->name); // => 'Jim'
var_dump($object->get('name')); // => 'Jim'
var_dump($object->get(array('name', 'age'))); // => array('name' => 'Jim', 'age' => 26)
var_dump($object->accessor('name')); // => 'Jim'
var_dump($object->as_array()); // => array('name' => 'Jim', 'age' => 26)
?>
```

### Relationships

The cure aims to provide some ease when it comes to describing
and dealing with relationships between models. This ease comes
in the form of the `Magic` model and a set of `Relationships`
classes. Currently there are `HasOne`, `HasMany`,
`BelongsToOne` and `BelongsToMany`.

#### HasOne

To describe a parent's relationship with a single child you
can use the `TheCure\Relationships\Hasone` attribute
in your `TheCure\Models\Magic` model.

``` php
<?php
namespace TheCure\Models;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Models\Magic as MagicModel;

use TheCure\Relationships\HasOne;

class Account extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('email'),
			new HasOne('password', array(
				'mapper_suffix' => 'Password',
			)));
	}

}
?>
```

Here we described `TheCure\Models\Account` and it's
`HasOne` relationship with `Models\Password`.

``` php
<?php
namespace TheCure\Models;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Models\Magic as MagicModel;

use TheCure\Relationships\BelongsToOne;

class Password extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(new Field('password'));
	}

	public function __construct($password)
	{
		$this->password($password);
	}

}
?>
```

This is the `TheCure\Models\Password` class which has
a custom `::__construct()` taking a password.

Let's test the creation of this relationship and see if we
can pull the model back out.


``` php
<?php
$account = $container->mapper('Account')->model();
$password = $container->mapper('Password')->model(array('a password'));

// Setting the HasOne relationship
$account->password($password);
$container->mapper('Account')->save($account);

// Getting the HasOne relationship
$this->assertSame($password, $account->password());
?>
```

The `HasOne` attribute adds two methods to `Account`. These
are `::password()` and `::password($password)`, used for
getting and setting respectively.

See `test/unit/classes/TheCure/Acceptance/Relationships/HasOne.php`
for more information.

#### BelongsToOne

To describe a child's relationship with it's parent you can
use the `TheCure\Relationships\BelongsToOne` attribute
in your `TheCure\Models\Magic` model.

``` php
<?php
namespace TheCure\Models;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Models\Magic as MagicModel;

use TheCure\Relationships\BelongsToOne;

class Password extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('password'),
			new BelongsToOne('account', array(
				'mapper_suffix' => 'Account',
				'foreign'       => 'password',
			)));
	}

	public function __construct($password)
	{
		$this->password($password);
	}

}
?>
```

Here we added a `BelongsToOne` relationship to our previous
`Password` model. This method adds a getter to `Password`
called `::account()`.

``` php
<?php
// Getting the BelongsToOne relationship
$this->assertSame($account, $password->account());
?>
```

See `test/unit/classes/TheCure/Acceptance/Relationships/HasOne.php`
for more information.

#### HasMany

To describe a parent's relationship with it's children you can
use the `TheCure\Relationships\HasMany` attribute
in your `TheCure\Models\Magic` model.

``` php
<?php
namespace TheCure\Models\Forum;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\HasMany;
use TheCure\Models\Magic as MagicModel;

class Thread extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('title'),
			new Field('message'),
			new HasMany('posts', array(
				'mapper_suffix' => 'Forum\Post',
			)));
	}

}
?>
```

Here we have `TheCure\Models\Forum\Thread` which has
a `HasMany` relationship with `Forum\Post`.

``` php
<?php
namespace TheCure\Models\Forum;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\BelongsToOne;
use TheCure\Relationships\HasMany;
use TheCure\Models\Magic as MagicModel;

class Post extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('message'),
			new BelongsToOne('thread', array(
				'mapper_suffix' => 'Forum\Thread',
				'foreign'       => 'posts',
			)));
	}

}
?>
```

Here we have the `TheCure\Models\Forum\Post` model
which has a `BelongsToOne` relationship with `Forum\Thread`.

Let's see the relationship in action:

``` php
<?php
$thread = $container->mapper('Forum\Thread')->model();
$thread->title('Welcome thread');
$thread->message('<p>Welcome to the forum!</p>');

$post = $container->mapper('Forum\Post')->model();
$post->message('<p>Wuhat a great welcome this is :D</p>');

// Adding a relationship
$thread->add_posts($post);

$container->mapper('Forum\Thread')->save($thread);

// Getting the HasMany relationship
$this->assertSame($post, $thread->posts()->current());

// Getting the BelongsToOne relationship
$this->assertSame($thread, $post->thread());
?>
```

See `test/unit/classes/TheCure/Acceptance/Relationships/HasMany.php`
for more information.

#### BelongsToMany

To describe a child's relationship with it's many parents you
can use the `TheCure\Relationships\BelongsToMany`
attribute in your `TheCure\Models\Magic` model.

``` php
<?php
namespace TheCure\Models\Forum;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\BelongsToOne;
use TheCure\Relationships\HasMany;
use TheCure\Models\Magic as MagicModel;

class Post extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('message'),
			new BelongsToOne('thread', array(
				'mapper_suffix' => 'Forum\Thread',
				'foreign'       => 'posts',
			)),
			new HasMany('tags', array(
				'mapper_suffix' => 'Forum\Tag',
			)));
	}

}
?>
```

Here we update `TheCure\Models\Forum\Post` to have a
`HasMany` relationship with `Forum\Tag`.

``` php
<?php
namespace TheCure\Models\Forum;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Relationships\BelongsToMany;
use TheCure\Models\Magic as MagicModel;

class Tag extends MagicModel {
	
	public static function attributes()
	{
		return new Attributes(
			new Field('name'),
			new BelongsToMany('posts', array(
				'mapper_suffix' => 'Forum\Post',
				'foreign'       => 'tags',
			)));
	}

}
?>
```

This is `TheCure\Models\Forum\Tag` which has a
`BelongsToMany` relationship with `Forum\Post`.

Here is them in action:

``` php
<?php
namespace TheCure\Acceptance\Relationships;

/**
 * @group  acceptance
 * @group  relationships
 * @group  relationships.manytomany
 */

use TheCure\Acceptance\Acceptance;
use TheCure\Container;

class HasAndBelongsToMany extends Acceptance {

	protected function createPost($container, $message)
	{
		$post = $container->mapper('Forum\Post')->model();
		$post->message($message);
		return $post;
	}

	protected function createTag($container, $name)
	{
		$tag = $container->mapper('Forum\Tag')->model();
		$tag->name($name);
		return $tag;
	}

	/**
	 * @dataProvider  provideContainers
	 */
	public function testItShouldWork($container)
	{
		$firstPost = $this->createPost(
			$container,
			'<p>What a great welcome this is :D</p>');

		$secondPost = $this->createPost(
			$container,
			'<p>Cool stuff</p>');

		$coolTag = $this->createTag($container, 'cool');
		$irrelevantTag = $this->createTag($container, 'irrelevant');
		
		// Adding to HasMany relationship
		$firstPost->add_tags($coolTag);

		// And some more
		$secondPost->add_tags($coolTag);
		$secondPost->add_tags($irrelevantTag);

		$container->mapper('Forum\Post')->save($firstPost);
		$container->mapper('Forum\Post')->save($secondPost);

		// Getting HasMany relationship
		$this->assertSame(1, $firstPost->tags()->count());
		$this->assertSame(2, $secondPost->tags()->count());

		// Getting BelongsToMany relationship
		$this->assertSame(2, $coolTag->posts()->count());
		$this->assertSame(1, $irrelevantTag->posts()->count());
	}

}
?>
```

See `test/unit/classes/TheCure/Acceptance/Relationships/HasAndBelongsToMany.php`
for more information.

## Magic free edition

[!!] TODO

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

### Unit testing your domain logic

The cure imposes minimal logic on it's base domain objects.
Even `TheCure\Models\Magic` is fairly minimal in the
logic it exposes to your own domains. This isn't by accident,
we have made this decision by design. Our mapper logic, or
your own mapper logic should never be tested along side your
applications domain logic. They are unrelated and coupling
them together will add headaches in unit testing and
elsewhere.

#### A banking example

So in this example we're going to design, test and then build
a very simple bank account object.

We only expect this bank account to be able to transfer money
to another account. This is what I came up with:

``` php
<?php
$lukes_bank_account = new Models\BankAccount;
$bobs_bank_account = new Models\BankAccount;
$lukes_bank_account->transfer_money($bobs_bank_account, 100);
?>
```

So we have one object, one method and two parameters. Pretty
simple design, let's test it.

``` php
<?php
class ModelBankAccountTest extends PHPUnit_Framework_TestCase {

	public function provideBankAccounts()
	{
		return array(
			array(new Models\BankAccount, new Models\BankAccount),
		);
	}
	
	/**
	 * @dataProvider  provideBankAccounts
	 */
	public function testItShouldTransferMoneyFromOneAccountToAnother(
		$lukesAccount,
		$bobsAccount)
	{
		$lukesAccount->__object()->balance = 100;
		$bobsAccount->__object()->balance = 0;

		$lukesAccount->transfer_money($bobsAccount, 100);
		
		$this->assertSame(0, $lukesAccount->__object()->balance);
		$this->assertSame(100, $bobsAccount->__object()->balance);
	}

}
?>
```

First run your tests, they should fail... we haven't written
the BankAccount model yet! We have a test to prove the balance
transfers to another account.

Let's implement the bare minimum:

``` php
<?php
namespace Models;

class BankAccount extends \TheCure\Models\Model {

	public function transfer_money(BankAccount $account, $amount)
	{
		$this->__object()->balance -= $amount;
		$account->__object()->balance += $amount;
	}

}
?>
```

Running the unit test will show this code passes.

#### More examples

Checkout the `test` directory for more examples. In particular
various use cases are tested in
`test/unit/classes/TheCure/Acceptance`. You will also
find specifications in 
`test/unit/classes/TheCure/Specs`.

## License

Soon to be licensed, currently here for you to play with.

## Authors

The Gignite Team and anyone willing to contribute.

### Gignite are hiring!

If you like working on open source projects like this as well
as the opportunity to build Gignite with us then get in touch!

[http://gun.io/careers/959/php-developer](http://gun.io/careers/959/php-developerb)