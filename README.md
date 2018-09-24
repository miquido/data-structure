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
$map->has('name'); // true
$map->hasAll('name', 'email'); // false
$map->hasOneOf('name', 'email'); // true
$map->get('name'); // 'John'
$map->keys(); // new StringCollection('name', 'surname', 'age')
$map->values(); // ['John', 'Smith', 30]
$map->toArray(); // ['name' => 'John', 'surname' => 'Smith', 'age' => 30]

// remove() returns new Map without provided keys,
// in example below: new Map(['surname' => 'Smith'])
$map->remove('age', 'name');

// set() returns new Map(['name' => 'John', 'surname' => 'Smith', 'age' => 30, 'email' => 'john@smith'])
// if key already exists set() overwrites current value 
$map->set('email', 'john@smith');

// pick() creates new Map with selected keys
$map->pick('name', 'age'); // new Map(['name' => 'John', 'age' => 30])

// filterByKeys() returns new Map with values for which callback returns true
// similar methods: filter() and filterByKeys()
$map->filterByKeys(function (string $key): bool { 
    return \strpos($key, 'name') !== false; 
});
```
Check **[Miquido\DataStructure\Map\MapInterface](src/Map/MapInterface.php)** for all available methods.

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
$collection->getAll(); // [$user1, $user2]
$collection->toArray(); // [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'James']]

// map() transforms all items in collection via callback
// in example below: map() creates new MapCollection containing Map objects with capitalized names 
$collection->map(function (MapInterface $user): MapInterface {
    return $user->set('name', $user->getValue('name')->toStringValue()->toUpper());
});

// filter() returns new MapCollection with Map objects  for which callback returns true
$collection->filter(function (MapInterface $user): bool {
    return $user->getValue('id')->int() > 1;
});

// return first matching Map object, or throws Miquido\DataStructure\Exception\ItemNotFoundException  
$collection->find(function (MapInterface $user): bool {
    return $user->getValue('id')->int() > 1; 
});

```
Check **[Miquido\DataStructure\Map\MapCollectionInterface](src/Map/MapCollectionInterface.php)** for all available methods.

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

// all methods below return new StringCollection with modified state 
$strings->push('sit', 'amet'); // new StringCollection('lorem', 'ipsum', 'dolor', 'sit', 'amet')
$strings->remove('ipsum', 'lorem'); // new StringCollection('dolor')
$strings->map('strrev'); // new StringCollection('merol', 'muspi', 'rolod')
$strings->toLowerCaseAll(); // alias to $strings->map('mb_strtolower')  
$strings->toUpperCaseAll(); // alias to $strings->map('mb_strtoupper')  
$strings->trimAll(); // alias to $strings->map('trim')  

```
Check **[Miquido\DataStructure\TypedCollection\StringCollectionInterface](src/TypedCollection/StringCollectionInterface.php)** for all available methods.

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

Check **[Miquido\DataStructure\TypedCollection\IntegerCollectionInterface](src/TypedCollection/IntegerCollectionInterface.php)** for all available methods.

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

Check **[Miquido\DataStructure\TypedCollection\NumberCollectionInterface](src/TypedCollection/NumberCollectionInterface.php)** for all available methods.
 
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

Check **[Miquido\DataStructure\TypedCollection\ObjectCollectionInterface](src/TypedCollection/ObjectCollectionInterface.php)** for all available methods.

### Value
Represents a mixed value, useful when your data comes from unknown source and you want to get a specific data type.

See examples below: 
```php
<?php

use Miquido\DataStructure\Value\Value;

$value = new Value('lorem ipsum');
$value->getRawValue(); // 'lorem ipsum'
$value->string(); // 'lorem ipsum'
$value->toScalarValue(); // new ScalarValue('lorem ipsum')
$value->toStringValue(); // new StringValue('lorem ipsum')
$value->toCollectionValue(); // new CollectionValue(['lorem ipsum'])
```

```php
<?php

use Miquido\DataStructure\Value\Value;

$value = new Value(1537791526);
$value->string(); // '1537791526'
$value->int(); // 1537791526
$value->toNumberValue(); // new NumberValue(1537791526)
$value->dateTime()->format('Y-m-d'); // '2018-09-24'
```

```php
<?php

use Miquido\DataStructure\Value\Value;

$value = new Value('false');
$value->bool(); // false - string is parsed, so 'false' 'no' 'null' or '0' are casted to false
$value->bool(false); // true - because 'false' is not an empty string
```

```php
<?php

use Miquido\DataStructure\Value\Value;

$value = new Value(['lorem', 'ipsum']);
$value->toCollectionValue()->strings(); // new StringCollection('lorem', 'ipsum')

```

Check **[Miquido\DataStructure\Value\ValueInterface](src/Value/ValueInterface.php)** for all available methods.

### ScalarValue

Check **[Miquido\DataStructure\Value\Scalar\ScalarValueInterface](src/Value/Scalar/ScalarValueInterface.php)** for all available methods.

### StringValue

Check **[Miquido\DataStructure\Value\Scalar\String\StringValueInterface](src/Value/Scalar/String/StringValueInterface.php)** for all available methods.

### NumberValue

Check **[Miquido\DataStructure\Value\Scalar\Number\NumberValueInterface](src/Value/Scalar/Number/NumberValueInterface.php)** for all available methods.

### CollectionValue

Check **[Miquido\DataStructure\Value\Collection\CollectionValueInterface](src/Value/Collection/CollectionValueInterface.php)** for all available methods.


## Contributing

Pull requests, bug fixes and issue reports are welcome.
Before proposing a change, please discuss your change by raising an issue.
