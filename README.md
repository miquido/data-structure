[![Build](https://travis-ci.org/miquido/data-structure.svg?branch=master)](https://travis-ci.org/miquido/data-structure)
[![Maintainability](https://api.codeclimate.com/v1/badges/edbdc45e25c5b6e876f0/maintainability)](https://codeclimate.com/github/miquido/data-structure/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/edbdc45e25c5b6e876f0/test_coverage)](https://codeclimate.com/github/miquido/data-structure/test_coverage)
[![MIT Licence](https://badges.frapsoft.com/os/mit/mit.svg?v=103)](https://opensource.org/licenses/mit-license.php)

# Data-structure

Set of utility classes for immutable data manipulation.

- [Installation guide](#installation)
- [Examples](#examples)
- [Contributing](#contributing)

## Installation

Data-structure is available on [Packagist](https://packagist.org/packages/miquido/data-structure), 
and installation via [Composer](https://getcomposer.org) is the recommended way to install it. 
To start using it just run following command from your project directory:

```shell
composer require miquido/data-structure
```

or simply add this line to your `composer.json` file:

```
"miquido/data-structure": "dev-master"
```

## Examples

- [Map](#map)
- [MapCollection](#mapcollection)
- [StringCollection](#stringcollection)
- [IntegerCollection](#integercollection)
- [NumberCollection](#numbercollection)
- [ObjectCollection](#objectcollection)
- [Value](#value)
- [ScalarValue](#scalarvalue)
- [StringValue](#stringvalue)
- [NumberValue](#numbervalue)
- [CollectionValue](#collectionvalue)

IMPORTANT! All methods across all classes are immutable - they do not modify internal state of the object, they return new class instance with a new state.

### Map
Immutable wrapper for associative array.

```php
<?php

use Miquido\DataStructure\Map\Map;

$map = new Map(['name' => 'John', 'surname' => 'Smith', 'age' => 30]);

$map->count(); // 3
\count($map); // 3 - Map implements \Countable interface
// Map also implements \ArrayAccess, but only for accessing the data
isset($map['name']); // true
$map['name']; // 'John'

$map->has('name'); // true
$map->hasAll('name', 'email'); // false
$map->hasOneOf('name', 'email'); // true

$map->get('name'); // 'John'
$map->getValue('name'); // new Value('John')

$map->keys(); // new StringCollection('name', 'surname', 'age')
$map->keys()->values(); // ['name', 'surname', 'age']
$map->values(); // ['John', 'Smith', 30]
$map->toArray(); // ['name' => 'John', 'surname' => 'Smith', 'age' => 30]

$map = new Map(['name' => 'John', 'surname' => 'Smith']);
$map2 = $map->set('email', 'john@smith'); // new Map(['name' => 'John', 'surname' => 'Smith', 'email' => 'john@smith'])
$map3 = $map2->remove('email'); // new Map(['name' => 'John', 'surname' => 'Smith'])

$map->has('email'); // false
$map2->has('email'); // true
$map3->has('email'); // false
$map->equals($map2); // false
$map->equals($map3); // true

// other immutable methods:
$map->rename('name', 'firstName')->rename('surname', 'lastName'); // new Map(['firstName' => 'John', 'lastName' => 'Smith'])
$map->pick('name'); // new Map(['name' => 'John'])
$map->mapKeys(function (string $key): string {
    return \sprintf('__%s__', \strtoupper($key)); // new Map(['__NAME__' => 'John', '__SURNAME__' => 'Smith']);
});

$map = new Map(['2016' => 50, '2017' => 150, '2018' => 250]);
$map->filter(function (int $value, string $key): bool { 
    return $value > 200 || $key === '2016'; // new Map(['2016' => 50, '2018' => 250]) 
});
$map->filterByKeys(function (string $key): bool {
    return \in_array($key, ['2016', '2017'], true); // new Map(['2016' => 50, '2017' => 150])
});
$map->filterByValues(function (int $value): bool {
    return $value < 200; // new Map(['2016' => 50, '2017' => 150])
});

$map1 = new Map(['name' => 'John']);
$map2 = new Map(['surname' => 'Smith']);
$map1->merge($map2);  // new Map(['name' => 'John', 'surname' => 'Smith'])
```

### MapCollection

```php
<?php

use Miquido\DataStructure\Map\MapCollection;
use Miquido\DataStructure\Map\Map;
use Miquido\DataStructure\Map\MapInterface;

$user1 = new Map(['id' => 1, 'name' => 'John']);
$user2 = new Map(['id' => 2, 'name' => 'James']);

$collection = new MapCollection($user1, $user2);
$collection->count(); // 2

// map() creates new MapCollection with new Map objects with capitalized names 
$collection->map(function (MapInterface $user): MapInterface {
    return $user->set('name', $user->getValue('name')->toStringValue()->toUpper());
});

// returns new MapCollection with only $user2 map
$collection->filter(function (MapInterface $user): bool {
    return $user->getValue('id')->int() > 1;
});

// returns $user2
$collection->find(function (MapInterface $user): bool {
    return $user->getValue('id')->int() > 1; 
});

$collection->findByKeyAndValue('name', 'John'); // returns $user1

$collection->getAll(); // [$user1, $user2]
$collection->toArray(); // [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'James']]

```

### StringCollection

Represents array of strings with some useful methods. 

```php
<?php

use Miquido\DataStructure\TypedCollection\StringCollection;

$strings = new StringCollection('lorem', 'ipsum', 'dolor');
$strings->count(); // 3
$strings->includes('ipsum'); // true
$strings->join('-'); // 'lorem-ipsum-dolor'
$strings->values(); // ['lorem', 'ipsum', 'dolor']

// all methods are immutable
$strings->push('sit', 'amet'); // new StringCollection('lorem', 'ipsum', 'dolor', 'sit', 'amet')
$strings->remove('ipsum', 'lorem'); // new StringCollection('dolor')
$strings->map('strrev'); // new StringCollection('merol', 'muspi', 'rolod')
$strings->toLowerCaseAll(); // alias to $strings->map('mb_strtolower')  
$strings->toUpperCaseAll(); // alias to $strings->map('mb_strtoupper')  
$strings->trimAll(); // alias to $strings->map('trim')  

$strings = new StringCollection('lorem', '', 'ipsum', '');
$strings->filter(function (string $value): bool {
    return empty($value); // new StringCollection('', '')
});
$strings->filterNotEmpty(); // new StringCollection('lorem', 'ipsum')
$strings->filterNotIn('', 'ipsum'); // new StringCollection('lorem')

$strings = new StringCollection('lorem', 'lorem', 'ipsum', 'dolor', 'dolor', 'sit');
$strings->unique(); // new StringCollection('lorem', 'ipsum', 'dolor', 'sit');
$strings->duplicates(); // new StringCollection('lorem', 'dolor');
```

### IntegerCollection

Represents array of integers with some useful methods. 
```php
<?php

use Miquido\DataStructure\TypedCollection\IntegerCollection;

$integers = new IntegerCollection(1, 1, 2, 2);
$integers->count(); // 4
$integers->values(); // [1, 1, 2, 2]
$integers->includes(1); // true
$integers->push(3); // new IntegerCollection(1, 1, 2, 2, 3)
$integers->unique(); // new IntegerCollection(1, 2)
```

### NumberCollection
Similar to IntegerCollection, but also allows floats.
```php
<?php

use Miquido\DataStructure\TypedCollection\NumberCollection;

$integers = new NumberCollection(1.1, 1.2, 2.1, 2.1);
$integers->count(); // 4
$integers->values(); // [1.1, 1.2, 2.1, 2.1]
$integers->includes(1.1); // true
$integers->push(3.5); // new NumberCollection(1.1, 1.2, 2.1, 2.1, 3.5)
$integers->unique(); // new NumberCollection(1.1, 1.2, 2.1)
```
 
### ObjectCollection
```php
<?php

use Miquido\DataStructure\TypedCollection\ObjectCollection;

class User 
{
    public $id;
    public $name;
    
    public function __construct(int $id, string $name) 
    {
        $this->id = $id;
        $this->name = $name;
    }
}

$user1 = new User(1, 'John');
$user2 = new User(2, 'James');
$collection = new ObjectCollection($user1, $user2);
$collection->count(); // 2
$collection->getAll(); // [$user1, $user2]

// returns new Map(['john' => $user1, 'james' => $user2]) 
$collection->toMap(function (User $user): string {
    // callback has to provide a unique key for each object
    return \strtolower($user->name);
});

```


### Value
Represents a mixed value, useful when your data comes from unknown source and you want to get a specific data type.

See examples below: 
```php
<?php

use Miquido\DataStructure\Value\Value;

/*
public function toMap(): MapInterface;
public function toCollectionValue(bool $castScalar = true): CollectionValueInterface;
public function toNumberValue(): NumberValueInterface;
public function int(): int;
public function float(): float;
public function bool(bool $parseString = true): bool;
public function dateTime(): \DateTime;
*/

$value = new Value('lorem ipsum');
$value->getRawValue(); // 'lorem ipsum'
$value->string(); // 'lorem ipsum'
$value->toScalarValue(); // new ScalarValue('lorem ipsum')
$value->toStringValue(); // new StringValue('lorem ipsum')
$value->toCollectionValue(); // new CollectionValue(['lorem ipsum'])

$value = new Value(1537791526);
$value->string(); // '1537791526'
$value->int(); // 1537791526
$value->toNumberValue(); // new NumberValue(1537791526)
$value->dateTime()->format('Y-m-d'); // '2018-09-24'

$value = new Value('false');
$value->bool(); // false - string is parsed, so 'false' 'no' 'null' or '0' are casted to false
$value->bool(false); // true - because 'false' is not an empty string

```

### ScalarValue
### StringValue
### NumberValue
### CollectionValue

```php
<?php

use Miquido\DataStructure\Value\Collection\CollectionValue;

$collection = new CollectionValue(['1', '2', '3']);
$collection->integers(); // new IntegerCollection(1, 2, 3)
$collection->numbers(); // new NumberCollection(1, 2, 3)
$collection->strings(); // new StringCollection('1', '2', '3')

/*
public function strings(): StringCollectionInterface;
public function numbers(): NumberCollectionInterface;

public function integers(): IntegerCollectionInterface;

public function objects(): ObjectCollectionInterface;

public function keys(): array;

public function values(): array;

public function get(): array;
*/
```


## Contributing

Pull requests, bug fixes and issue reports are welcome.
Before proposing a change, please discuss your change by raising an issue.
