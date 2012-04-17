# The Cure

> Each time I play a song it seems more real.
>
> *Robert Smith*

## General flow of using The Cure

 - Create a `MapperContainer` that is the DI container for all
   mappers and the objects they create
 - Get a mapper object from `MapperContainer` using `::use()`

``` php
<?php
$mapper = new MapperContainer('Mongo');
$mapper->use('Profile'); // => Mapper_Mongo_Profile
$mapper->use('Media');   // => Mapper_Mongo_Media
?>
```

### Creating

 - Create a new model
 - Validate the model
 - Pass the model to Mapper::save()

``` php
<?php
$image = new Model_Media_Image;

if ($image->create($owner, compact('filename')))
{
	$mapper->use('Media')->save($image);
}
?>
```

### Updating

 - Use Mapper::find_one()
 - Operate on the model
 - Validate the model
 - Pass the model to Mapper::save()

``` php
<?php
$image = $mapper->use('Media')->find_one('Image', $id); // => Model_Media_Image
$image->caption('A new caption.');

if ($image->validation()->check())
{
	$mapper->use('Media')->save($image);
}
?>
```

### Finding

 - Use Mapper::find_one() to find a single model

The update example includes a ::find_one() call which has two
arguments. Optionally you can simply pass in an ID like so:

``` php
<?php
$mapper->use('Profile')->find_one($id); // => Model_Profile
$mapper->use('Profile')->find_one('Artist', $id); // => Model_Profile_Artist
?>
```

### Deleting

 - Use Mapper::delete() to delete a model or by query.

``` php
<?php
$profile_mapper = $mapper->use('Profile');

// Using a model
$profile_mapper->delete($profile_mapper->find_one($id));

// Using an ID
$profile_mapper->delete($id);

// Using a query (unlimited)
$profile_mapper->delete(array('type' => 'artist'));
?>
```

## The relationship between a Model and it's Mappers

This is by convention only and it is completely possible to
not follow it.

A Model describes the business (or domain) logic for a
particular area of expertise. For example a Model might
describe a user of an application. This would likely be called
`Model_User`. The model would have methods for registering an
account, updating it's email address, etc.

In order to store `Model_User` in a Mongo database we need a
mapper. A mapper knows how to store a model's data into what
ever medium it wants. A mongo mapper for a user model would,
by convention, be called `Mapper_Mongo_User`.

If you wanted to test `Model_User` you would also need another
mapper. This mapper would be a mock and would be used to test
the data before and after an operation on your model. By
convention this would then be called `Mapper_Mock_User`.

This convention explains the API choice of
`MapperContainer::__construct()`, `MapperContainer::use()` and
`Mapper::find_one()`:

### MapperContainer::__construct($type)

The argument passed here is added onto the `Mapper_` prefix,
so for example `new MapperContainer('Mongo')` creates a prefix
for all mappers called `Mapper_Mongo_`.

### MapperContainer::use($mapper)

The argument passed to ::use() indicates the domain area being
mapped so for example `$container->use('Profile')` will
instantiate a single `Mapper_Mongo_Profile` and register it
with the container so that subsequent calls return the same
instance.

### Mapper::find_one($suffix, $id)

The first argument passed to `::find_one()` is a suffix for
the model class name which is by default a derivative of the
mapper class name. Take this example:

``` php
<?php
$container = new MapperContainer('Mongo');

// Using Mapper_Mongo_Profile to find Model_Profile_Artist
$model = $container->use('Profile')->find_one('Artist', $id);

// Using Mapper_Mongo_Profile to find Model_Profile
$model = $container->use('Profile')->find_one($id);
?>
```

## Notes

 - `MapperContainer` is currently locked down to Mongo
   connections, this doesn't matter to me at the moment
 - `Mapper` classes need an IdentityMap to ensure only one
   object is ever created per document
 - Collections (and Cursors) do not exist yet