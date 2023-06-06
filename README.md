# extas-repo-toolkit

Tool KIt for Extas repositories

![PHP Composer](https://github.com/jeyroik/extas-repo-toolkit/workflows/PHP%20Composer/badge.svg?branch=master)
![codecov.io](https://codecov.io/gh/jeyroik/extas-repo-toolkit/coverage.svg?branch=master)

[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-repo-toolkit/v)](//packagist.org/packages/jeyroik/extas-repo-toolkit)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-repo-toolkit/downloads)](//packagist.org/packages/jeyroik/extas-repo-toolkit)
[![Dependents](https://poser.pugx.org/jeyroik/extas-repo-toolkit/dependents)](//packagist.org/packages/jeyroik/extas-repo-toolkit)


# usage

Typically you'll use this package in an `extas.storage` configuration with the `jeyroik/extas-config-php` plugin.

So as precondition you have an `extas.storage.php` (or a `extas.app.storage.php`):

```php
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                
            ]
        ],
    ]
];
```
Current tollkit is supposed to use in the `code` section, see examples below.

## generate uuid for an item

```php
use extas\components\repositories\tools\RepoItem
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                // if your item implements extas\interfaces\IHaveUUID, if not, please use ::setUuid($item, 'id_field_name')
                "create-before" => '\\' . RepoItem::class . '::setId($item);'
            ]
        ],
    ]
];
```

## check item existing

```php
use extas\components\repositories\tools\RepoItem
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                "create-before" => '\\' . RepoItem::class . '::throwIfExist($this, $item, [\'fieldName\']);'
            ]
        ],
    ]
];
```

## check required fields

```php
use extas\components\repositories\tools\RepoItem
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                "create-before" => '\\' . RepoItem::class . '::require($item, [\'fieldName\']);'
            ]
        ],
    ]
];
```

## hashing item field

```php
use extas\components\repositories\tools\RepoItem
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                "create-before" => '\\' . RepoItem::class . '::sha1($item, [\'fieldName\']);'
            ]
        ],
    ]
];
```

## encrypting item field

```php
use extas\components\repositories\tools\RepoItem
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                // openssl with key encrypting is used by default, you can determ another way by passing third param
                // see Encryption section below
                "create-before" => '\\' . RepoItem::class . '::encrypt($item, [\'fieldName\']);',
                "one-after" => '\\' . RepoItem::class . '::decrypt($item, [\'fieldName\'])'
            ]
        ],
    ]
];
```

## combine several checks at once

```php
use extas\components\repositories\tools\RepoItem
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                "create-before" => '\\' . RepoItem::class . '::multiple($this, $item, [\'setId\' => '',...]);'
            ]
        ],
    ]
];
```

Don't forget you can move hard code into another file and attach it:

```php
//resources/multicheck.php
/*
 * @var IItem $item
 * @var IRepository $this
 */

return RepoItem::multiple($this, $item, [
    'setId' => ['field1'],
    'require' => ['field1', 'field2'],
    'throwIfExist' => ['field1'],
    //...
]);

//extas.storage.php
use extas\components\repositories\tools\Injector
return [
    //...,
    'tables' => [
        "my_entities" => [
            "namespace" => "repositories",
            "item_class" => "\\myvendor\\components\\myentities\\MyEntity",
            "pk" => "id",
            "code" => [
                "create-before" => Injector::get('/resources/multicheck')
            ]
        ],
    ]
];
```