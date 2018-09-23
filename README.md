[![Build](https://travis-ci.org/miquido/data-structure.svg?branch=master)](https://travis-ci.org/miquido/data-structure)
[![Maintainability](https://api.codeclimate.com/v1/badges/edbdc45e25c5b6e876f0/maintainability)](https://codeclimate.com/github/miquido/data-structure/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/edbdc45e25c5b6e876f0/test_coverage)](https://codeclimate.com/github/miquido/data-structure/test_coverage)
[![MIT Licence](https://badges.frapsoft.com/os/mit/mit.svg?v=103)](https://opensource.org/licenses/mit-license.php)

# Data-structure

Set of utility classes for immutable data manipulation.

- [Installation guide](#installation)
- [Examples](#Examples)
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

- [Map](#Map)
- [MapCollection](#MapCollection)
- [StringCollection](#StringCollection)
- [IntegerCollection](#IntegerCollection)
- [NumberCollection](#NumberCollection)
- [ObjectCollection](#ObjectCollection)
- [Value](#Value)
- [ScalarValue](#ScalarValue)
- [StringValue](#StringValue)
- [NumberValue](#NumberValue)
- [CollectionValue](#CollectionValue)

### Map
Immutable wrapper on associative array.

```php
<?php

use Miquido\DataStructure\Map\Map;

$map = new Map(['name' => 'John', 'surname' => 'Smith', 'age' => 30]);

$map->count(); // 3
\count($map); // 3 - implements \Countable interface

$map->has('name'); // true
isset($map['name']); // true
$map->hasAll('name', 'email'); // false
$map->hasOneOf('name', 'email'); // true

$map->get('name'); // 'John'
$map->getValue('name'); // new Value('John')
$map['name']; // 'John' - implements \ArrayAccess

$map->keys(); // new StringCollection('name', 'surname', 'age')
$map->keys()->values(); // ['name', 'surname', 'age']
$map->values(); // ['John', 'Smith', 30]
$map->toArray(); // ['name' => 'John', 'surname' => 'Smith', 'age' => 30]
```

All methods that modify data return new Map object.

```php
<?php

use Miquido\DataStructure\Map\Map;

$map = new Map(['name' => 'John', 'surname' => 'Smith']);
$map2 = $map->set('email', 'john@smith'); // returns new Map object
$map3 = $map2->remove('email');

$map->has('email'); // false
$map2->has('email'); // true
$map3->has('email'); // false
$map->equals($map2); // false
$map->equals($map3); // true

// other immutable methods:
$map->rename('name', 'firstName')->rename('surname', 'lastName'); // returns new Map(['firstName' => 'John', 'lastName' => 'Smith'])
$map->pick('name'); // returns new Map(['name' => 'John'])
$map->mapKeys(function (string $key): string {
    return \sprintf('__%s__', \strtoupper($key)); // returns new Map(['__NAME__' => 'John', '__SURNAME__' => 'Smith']);
});

$map = new Map(['2016' => 50, '2017' => 150, '2018' => 250]);
$map->filter(function (int $value, string $key): bool { 
    return $value > 200 || $key === '2016'; // returns new Map(['2016' => 50, '2018' => 250]) 
});
$map->filterByKeys(function (string $key): bool {
    return \in_array($key, ['2016', '2017'], true); // returns new Map(['2016' => 50, '2017' => 150])
});
$map->filterByValues(function (int $value): bool {
    return $value < 200; // returns new Map(['2016' => 50, '2017' => 150])
});

$map1 = new Map(['name' => 'John']);
$map2 = new Map(['surname' => 'Smith']);
$map1->merge($map2);  // returns new Map(['name' => 'John', 'surname' => 'Smith'])
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

$collection->getAll(); // returns [$user1, $user2]
$collection->toArray(); // returns [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'James']]

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
### NumberCollection
### ObjectCollection
### Value

```php
<?php

use Miquido\DataStructure\Value\Value;

$value = new Value(' lorem ipsum ');
$value->string(); // ' lorem ipsum '
$value->toStringValue()->trim()->toUpper()->get(); // 'LOREM IPSUM'
$value->toStringValue()->split(' ')->values(); // ['lorem', 'ipsum']
```

### ScalarValue
### StringValue
### NumberValue
### CollectionValue


## Contributing

Pull requests, bug fixes and issue reports are welcome.
Before proposing a change, please discuss your change by raising an issue.
