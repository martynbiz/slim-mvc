# SlimMVC #

Easily add controllers to a Slim Framework v3 application.

## Controller ##

/app/routes.php

```php
<?php

// index routes (homepage, about, etc)
$app->group('', function () use ($app) {

    $controller = new App\Controller\IndexController($app);

    $app->get('/', $controller('index'));
    $app->get('/contact', $controller('contact'));
});

// create resource method for Slim::resource($route, $name)
$app->group('/articles', function () use ($app) {

    $controller = new App\Controller\ExampleController($app);

    $app->get('/{id:[0-9]+}', $controller('show'));
    $app->get('/{id:[0-9]+}/{slug}', $controller('show'));
});
```

/app/controllers/ExampleController.php

```php
<?php

namespace App\Controller;

use SlimMvc\Http\Controller;

class ExampleController extends Controller
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

## Dependencies within controllers ##

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
class ExampleController extends Controller
{
    public function index()
    {
        $examples = $this->get('model.example')->find();

        return $this->render('admin/example/index.html', array(
            'examples' => $examples,
        ));
    }
    .
    .
    .
```


## Testing controllers ##

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
        $container = $this->app->getContainer();
        container->set('model.article', $articleMock);

        $this->assertController('articles');
        $this->assertAction('index');
        $this->assertStatusCode(200);
        $this->assertQuery('table#examples');
        $this->assertQueryCount('div.errors', 0);
        // $this->assertRedirects();
        // $this->assertRedirectsTo('...');
    }
}
```

## TODO

crsrc
* acl - roles, admin menus
* admin-lte template - create form: title box, content box, location box, tags
* article create: slug, approve, ckeditor
* homepage - latest
* route /{region}/{id}/{slug} -- on the fly routes (regions collection), getUrl() eg. /glasgow/123/green-shop
* route /{tag}
* map - lat/lng, location box on form
* path_for all links
* pagination? $this->service('model.articles')->find(...)->paginate(20);
* csrf - ask question on SO
* flash -- my flash isn't working, SO?
* gulp?
* psr-4, move controllers to library
* create project that can easily be installed (martynbiz/slim3-project)
v2
* contributers/ submit articles for approval
* homepage - featured, latest
* photos (dropzone)
* video -- youtube uploads
* vagrant
* zend-like: bootstrap, resources (one for mongo, another for eloquent), -- __construct?
* popular - google analytics
* admin-lte homepage (graphs etc)

martynbiz/php-flush-messages




martynbiz/slim3-contoller
* update README - installation/ usage notes
* package up controller lib (slim3-controller)
tests
* can we run tests with run() instead? then we can use App for bootstrap (eg. routes)
* crud: update, delete
* query
* more appropriate error messages for TestCase's assert* methods

martynbiz/php-mongo
* mongo - how to handle created_at, updated_at
* mongo - soft deletes, deleted_at
* paginate
* access properties like: $user['username'] or $user->username
