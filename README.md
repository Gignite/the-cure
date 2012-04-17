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
$image = $mapper->use('Media')->find('Image', $id); // => Model_Media_Image
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
$mapper->use('Profile')->find($id); // => Model_Profile
$mapper->use('Profile')->find('Artist', $id); // => Model_Profile_Artist
?>
```

## Notes

 - `MapperContainer` is currently locked down to Mongo
   connections, this doesn't matter to me at the moment
 - `Mapper` classes need an IdentityMap to ensure only one
   object is ever created per document
 - Collections (and Cursors) do not exist yet