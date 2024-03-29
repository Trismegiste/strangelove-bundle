# Strangelove

![How I Learned to Stop Worrying about Doctrine and Love MongoDb BSON](https://github.com/Trismegiste/strangelove-bundle/blob/master/docs/strangelove-title.png)

A symfony bundle for stopping the chase after the mythical object-database, or its fake approximation a.k.a Doctrine.

## MongoDb
A almost-zero-config Object Data Mapper for MongoDB. It's a micro database layer with 
automatic mapping that rely on BSON types.
It is intended for **advanced users** of MongoDB
who know and understand the growth of a model on a schemaless database.

When I mean "micro", I mean the sum of NCLOC is about one hundred. Therefore it is fast as hell.

## Install
### Include the bundle
```bash
$ composer require trismegiste/strangelove-bundle
```
You'll probably get an error at cache clearing since the config file ```strangelove.yaml``` does not exist yet.
### Configure the bundle
Just run the wizard and answer the questions :
```bash
$ vendor/bin/strangelove-wizard.php
```
### Create a Repository on a collection
Create a subclass of ```Trismegiste\Strangelove\MongoDb\DefaultRepository``` and extend it with business features.

### Register the service
To register the repository class MyMovies on the collection 'movies' into the ```services.yaml``` config file, just add :
```yaml
services:
    # ... some services
    App\Repository\MyMovies:
        $collectionName: movies
```
## How
Since you have atomicity on one document in MongoDB, you have to store complex
tree-ish objects. If you avoid circular references, this ODM stores your object
in a comprehensive structure into a MongoDB collection.

Every object has to implement one interface and use one trait :

```php
class MyEntity implements \MongoDB\BSON\Persistable {
    use \Trismegiste\Strangelove\MongoDb\PersistableImpl;
}
```

The "top document" or the "root document", meaning the one who owns the primary key (a.k.a the field "_id" in MongoDB), must
implement the interface Root and use the trait RootImpl.

```php
class MyDocument implements \Trismegiste\Strangelove\MongoDb\Root {
    use \Trismegiste\Strangelove\MongoDb\RootImpl;
}
```
## Repositories
There is a default repository against a collection : ```DefaultRepository```.

### Loading
```php
$record = $myRepository->load($primaryKey);
```
### Persisting
```php
$myRepository->save($oneObjectInheritingFromRoot);
$rimaryKey = $oneObjectInheritingFromRoot->getPk();
```
### Searching
```php
$iter = $myRepository->search(['price' => ['$ne' => null]]);
foreach($iter as $record) {
   var_dump($record);
}
```

This class implements the interface Repository. Read the phpdoc about it.

## Types
* Don't use DateTime in your model, use BsonDateTime, you have a nice AbstractType for replacing Symfony DateType
that converts DateTime into BsonDateTime
* Don't use SplObjectStorage in your model, use BsonObjectStorage
* Don't use SplFixedArray in your model, use BsonFixedArray

Please read the documentation about BSON serialization in MongoDB to know
more : [The MongoDB\BSON\Persistable interface](https://www.php.net/manual/en/class.mongodb-bson-persistable.php)

## Performance
A thousand of complex objects that contain about a thousand embedded objects take 2.5 seconds to store on a cheap laptop.
And it takes about 1.8 seconds to load and hydrate.

## Internals
This ODM fully relies on BSON API for MongoDB. Your objects can be anything you want : no annotation, 
no constraint on constructor or extending some mandatory concrete class. 
Serialization and unserialization are made in the driver written in C, not PHP, that's why it is so fast.

## Tests
This library is full tested with PHPUnit. Simply run :
```bash
$ vendor/bin/phpunit
```

A full functional test can be found in DefaultRepositoryTest.php.

## Iterator
Currently, there is one Decorator for an Iterator object : ```ClosureDecorator```. It is useful for decorating iterators 
on cursors created by MongoDB repositories (see above)

## Code coverage
Code coverage configurations are included in the ```phpunit.xml```.
Just run :
```bash
$ phpdbg -qrr vendor/bin/phpunit
```

Html results are stored in ./docs/coverage.
