# A set of useful tools

## Iterator
Currently, there is one Decorator for an Iterator object : ClosureDecorator

## MongoDb
A zero-config ODM for MongoDB. It's a micro database layer with automatic mapping.
It is intended for **advanced users** of MongoDB
who know and understand the growth of a model on a schemaless database.

When I mean "micro", I mean the sum of NCLOC is about one hundred. Therefore it is fast as hell.

### How
Since you have atomicity on one document in Document, you have to store complex
tree-ish objects. If you avoid circular references, this ODM store your object
in a comprehensive structure into a MongoDB collection.

Every object has to implement the interface MongoDB\BSON\Persistable and use the trait Trismegiste\Toolbox\MongoDb\PersistableImpl.
The "top document" or the "root document", meaning the one who owns the primary key (a.k.a the field "_id" in MongoDB), must
implement the interface Trismegiste\Toolbox\MongoDb\Root and use the trait Trismegiste\Toolbox\MongoDb\RootImpl.

And that's it !

### Repositories
There is a default repository against a collection : DefaultRepository.
It implements the interface Repository. Read the phpdoc about it.

### Performance
A thousand of complex objects that contain about a thousand embedded objects take 2.5 seconds to store on a cheap laptop.
And it takes about 1.8 seconds to load and hydrate.

### Internals
This ODM fully relies on BSON API for MongoDB. Your objects can be anything you want : no constraint on constructor or extending
some mandatory class. Serialization and unserialization are made in the driver written in C, not PHP, that's why it is so fast.

### Tests
This library is full tested with PHPUnit. Simply run 'vendor/bin/phpunit'

## Useful functions

* join_paths() : this function glues a set of a chunked paths and prevents "double-slashing" like "/home//mypath//myfile.php".
No more ugly "__DIR__ . '/../' . 'myfile.php'" inside your source code, it transforms to "join_paths(__DIR__, '..', 'myfile.php')"
The functions take any number of parameters.