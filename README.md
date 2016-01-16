# SlimMVC #

Slim 3 with additional support attaching controllers to routes, and controller testing.

## Controllers ##

/app/routes.php

```php
<?php

$app->get('/', 'App\Controller\IndexController#home');
$app->get('/contact', 'App\Controller\IndexController#contact');

// RESTful routes
$app->resource('/example', 'App\Controller\ExampleController');
```

/app/controllers/ExampleController.php

```php
<?php

namespace App\Controller;

use SlimMvc\Controller\Base;

class ExampleController extends Base
{
    public function index()
    {
        return $this->render('admin/example/index.html', array(
            // data to pass to the view
        ));
    }

    public function show($id)
    {
        return $this->render('admin/example/show.html', array(
            // data to pass to the view
        ));
    }

    public function create()
    {
        return $this->render('admin/example/create.html');
    }

    public function post()
    {
        // handle create

        return $this->redirect('/admin/example');
    }

    public function edit($id)
    {
        return $this->render('admin/example/edit.html', array(
            // data to pass to the view
        ));
    }

    public function update($id)
    {
        // handle update

        return $this->redirect('/admin/example/' . $id);
    }
}
```

## Views & Layouts ##

### PHP templates ###

Coming soon

### Twig templates ###

/app/services.php

```php
$container['view'] = function ($container) {
    // TODO switch on caching for production
    $view = new \Slim\Views\Twig( APPLICATION_PATH . '/views/', [
        // 'cache' => realpath(APPLICATION_PATH . '/../cache/')
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};
```


/app/views/layout.html

```html
<html>
    <head>
        <title>{% block title %}SlimMvc Application{% endblock %}</title>
    </head>
    <body>
        <h1>Main</h1>
        {% block body %}{% endblock %}
    </body>
</html>
```

/app/views/example/index.html

```html
{% extends "layout.html" %}

{% block title %}Create new example{% endblock %}

{% block body %}
<table class="table table-striped">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Data created</th>
    </tr>
    {% for example in examples %}
    <tr>
        <td><a href="/admin/examples/{{ example.id }}">{{ example.title|e }}</a></td>
        <td>{{ example.description|e }}</td>
        <td>{{ example.created_at|e }}</td>
    </tr>
    {% endfor %}
</table>
{% endblock %}
```

## Services ##

/app/dependencies.php

```php
$container['model.example'] = function ($container) {
    return new App\Model\Example();
};
```

/app/controllers/ExampleController.php

```php
.
.
.
class ExampleController extends Base
{
    public function index()
    {
        $examples = $this->getService('model.example')->find();

        return $this->render('admin/example/index.html', array(
            'examples' => $examples,
        ));
    }
    .
    .
    .
```


## Testing ##

/phpunit.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit backupGlobals="false"
         bootstrap="tests/bootstrap.php">
    <testsuites>
        <testsuite name="Application Test Suite">
            <directory>./tests/</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

/tests/bootstrap.php

```php
// coming soon, still a little bloated
```

/tests/application/controllers/ExampleControllerTest.php

```php
<?php

use SlimMvc\Test\PHPUnit\TestCase;

class ExampleControllerTest extends TestCase
{
    .
    .
    .
    public function testApp()
    {
        $this->dispatch('/articles');

        // mock dependencies (optional)
        $this->setService('model.article', $articleMock);

        $this->assertController('articles');
        $this->assertAction('index');
        $this->assertStatusCode(200);
    }
}
```

Other built-in assertions (coming soon)

```php
$this->assertRedirects('...');
$this->assertViewReceives(array);
```

## TODO




MVC framework
* psr-4, move controllers to library
* cache views in prod
* create project that can easily be installed
* middleware: csrf, auth
* zend-like: bootstrap, resources (one for mongo, another for eloquent),
* flash
* mongo - how to handle created_at, updated_at
* mongo - soft deletes, deleted_at

tests:
* crud: update, delete
* query
* csrf
* more appropriate error messages for TestCase's assert* methods
