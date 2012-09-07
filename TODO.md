# Todo list

 - Add more method level documentation
 - Improve acceptance tests
 - Write guide for The Cure
 - Relationship attributes (extra data to be stored with the
   relationship IDs)
 - Use Model class name for foreign key field name
 - ManyToMany relationship (half completed already, we need
   ManyToManyVia still)
 - Need to handle namespacing and underscores in type passed
   to a new Container.
 - Set/get filters either via MagicModel or in the Object
   itself. Maybe not?
 - Provide non-magic example of a model
 - Lazy fields
 - Convert methods to camelCase
 - Add section to guide on configuration
 - Maybe also mongo specific informtion
 - Add MySQL mapper, do this as a tutorial
 - Collection::as() to alter classFactory
 - Split MapperActions into individual interfaces
 - Remove complex querying
 - Relation\Contains needs to rely on an unimplemented interface
 - Change class names to include their type as a suffix
 - Rename the SetGet interfaces
 - Group classes in type folders not any other way
 - Add transaction flushing (model persistence until its req.)
 - IDless relationships so we don't need to prematurely and
   secretly save child models