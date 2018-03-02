<p align="center">
    <a href="https://github.com/yii2tech" target="_blank">
        <img src="https://avatars2.githubusercontent.com/u/12951949" height="100px">
    </a>
    <h1 align="center">Admin pack for Yii 2</h1>
    <br>
</p>

This extension provides controllers, actions, widgets and other tools for administration panel creation in Yii2 project.

For license information check the [LICENSE](LICENSE.md)-file.

[![Latest Stable Version](https://poser.pugx.org/yii2tech/admin/v/stable.png)](https://packagist.org/packages/yii2tech/admin)
[![Total Downloads](https://poser.pugx.org/yii2tech/admin/downloads.png)](https://packagist.org/packages/yii2tech/admin)
[![Build Status](https://travis-ci.org/yii2tech/admin.svg?branch=master)](https://travis-ci.org/yii2tech/admin)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2tech/admin
```

or add

```json
"yii2tech/admin": "*"
```

to the require section of your composer.json.


Usage
-----

This extension provides controllers, actions, widgets and other tools for administration panel creation in Yii2 project.
These tools are meant to be used together for the rapid web application administration panel composition.

This package supports usage of following extensions:

 - [yii2tech/ar-position](https://github.com/yii2tech/ar-position)
 - [yii2tech/ar-role](https://github.com/yii2tech/ar-role)
 - [yii2tech/ar-search](https://github.com/yii2tech/ar-search)
 - [yii2tech/ar-softdelete](https://github.com/yii2tech/ar-softdelete)
 - [yii2tech/ar-variation](https://github.com/yii2tech/ar-variation)

> Note: none of these extensions is required by default, you'll need to install them yourself, if needed.


## Actions <span id="actions"></span>

This extension provides several independent action classes, which provides particular operation support:

 - [[\yii2tech\admin\actions\Index]] - displays the models listing with search support.
 - [[\yii2tech\admin\actions\Create]] - supports creation of the new model using web form.
 - [[\yii2tech\admin\actions\Update]] - supports updating of the existing model using web form.
 - [[\yii2tech\admin\actions\Delete]] - performs the deleting of the existing record.
 - [[\yii2tech\admin\actions\View]] - displays an existing model.
 - [[\yii2tech\admin\actions\SoftDelete]] - performs the "soft" deleting of the existing record.
 - [[\yii2tech\admin\actions\SafeDelete]] - performs the "safe" deleting of the existing record.
 - [[\yii2tech\admin\actions\Restore]] - performs the restoration of the "soft" deleted record.
 - [[\yii2tech\admin\actions\Callback]] - allows invocation of specified method of the model.
 - [[\yii2tech\admin\actions\Position]] - allows change custom sort position of the particular model.
 - [[\yii2tech\admin\actions\VariationCreate]] - supports creation of the new model with variations using web form.
 - [[\yii2tech\admin\actions\VariationUpdate]] - supports updating of the new model with variations using web form.
 - [[\yii2tech\admin\actions\RoleCreate]] - supports creation of the new model with role using web form.
 - [[\yii2tech\admin\actions\RoleUpdate]] - supports updating of the new model with role using web form.

Please refer to the particular action class for more details.

For example CRUD controller based on provided actions may look like following:

```php
namespace app\controllers;

use yii\web\Controller;

class ItemController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                '__class' => \yii2tech\admin\actions\Index::class,
                'newSearchModel' => function () {
                    return new ItemSearch();
                },
            ],
            'view' => [
                '__class' => \yii2tech\admin\actions\View::class,
            ],
            'create' => [
                '__class' => \yii2tech\admin\actions\Create::class,
            ],
            'update' => [
                '__class' => \yii2tech\admin\actions\Update::class,
            ],
            'delete' => [
                '__class' => \yii2tech\admin\actions\Delete::class,
            ],
        ];
    }

    public function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function newModel()
    {
        return new Item();
    }
}
```


## Controllers <span id="controllers"></span>

This extension provides several predefined controllers, which can be used as a base controller classes
while creating particular controllers:

- [[\yii2tech\admin\CrudController]] - implements a common set of actions for supporting CRUD for ActiveRecord.

Please refer to the particular controller class for more details.


## Widgets <span id="widgets"></span>

This  extension provides several widgets, which simplifies view composition for the typical use cases:

 - [[\yii2tech\admin\widgets\Alert]] - renders a message from session flash.
 - [[\yii2tech\admin\widgets\ActionAlert]] - renders an action proposition based on particular condition, usually a session flag.
 - [[\yii2tech\admin\widgets\ButtonContextMenu]] - simplifies rendering of the context links such as 'update', 'view', 'delete' etc.
 - [[\yii2tech\admin\widgets\Nav]] - enhanced version of [[\yii\bootstrap\Nav]], which simplifies icon rendering.

Also several enhancements for the [[\yii\grid\GridView]] are available:

- [[\yii2tech\admin\grid\ActionColumn]] - simplifies composition of the action buttons
- [[\yii2tech\admin\grid\DeleteStatusColumn]] - serves for the 'soft-deleted' status displaying
- [[\yii2tech\admin\grid\PositionColumn]] - provides simple interface for the model custom sort position switching
- [[\yii2tech\admin\grid\VariationColumn]] - allows displaying of the variation column values


## Using Gii <span id="using-gii"></span>

This extension provides a code generators, which can be integrated with yii 'gii' module.
In order to enable them, you should adjust your application configuration in following way:

```php
return [
    //....
    'modules' => [
        // ...
        'gii' => [
            '__class' => yii\gii\Module::class,
            'generators' => [
                'adminMainFrame' => [
                    '__class' => yii2tech\admin\gii\mainframe\Generator::class
                ],
                'adminCrud' => [
                    '__class' => yii2tech\admin\gii\crud\Generator::class
                ]
            ],
        ],
    ]
];
```

"MainFrame" generator creates a basic admin panel code, which includes layout files, main controller
file and basic view files. The created structure is necessary for the correct rendering of the code created
by "Admin CRUD" generator.

"Admin CRUD" generator is similar to regular "CRUD" generator, but it generates code, which uses tools from
this extension, so the result code is much more clean.


## Internationalization <span id="internationalization"></span>

All text and messages introduced in this extension are translatable under category 'yii2tech-admin'.
You may use translations provided within this extension, using following application configuration:

```php
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'yii2tech-admin' => [
                    '__class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@yii2tech/admin/messages',
                ],
                // ...
            ],
        ],
        // ...
    ],
    // ...
];
```
