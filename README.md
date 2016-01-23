# SlimMVC #

Slim Framework v3 with the help of some friends to make a base project.

## Installation ##

The following commands will bring all the required files to your local machine:

```
$ git clone ... myproject
$ cd myproject
$ composer install
```

The easiest way to run the application is to use the built-in PHP server:

```
$ php -S localhost:8080 -t public
```

## Controllers ##

Controllers use Slim3Controller:

https://github.com/martynbiz/slim3-controller

## Views ##

Views use twig templates:

https://github.com/slimphp/Twig-View
http://twig.sensiolabs.org/documentation

## Assets ##

Assets are managed by Gulp. Gulp tasks are defined in /gulpfile.js

To compile css/js, run:

```
$ npm install
```

Once changes have been made to the LESS or JavaScript files, run to compile:

```
$ gulp
```

To compile individually, run:

```
$ gulp css
$ gulp js
```

To watch for changes and not have to worry about manually compiling, run:

```
$ gulp watch
```

## Models ##

## Cache ##

## Testing ##

## Flash messages ##

## Sessions ##

## Authentication ##






## TODO

buy wordup.com

testing - admin/articles with acl method calls mocked
* codeception
* mockery
* phpspec

* acl - canEdit(...)
* martynbiz/wordup -- core stuff
* ckeditor
* set author
* user management - create user, delete, edit (e.g. roles)
* admin-lte template - create form: title box, content box, location box, tags
* Maybe we don't need to pass in $container stuff after all http://stackoverflow.com/questions/34839399/how-to-access-the-container-within-middleware-class-in-slim-v3/34930473#34930473
* test alternatives to phpunit

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

martynbiz/php-flash-message
* has('success')



martynbiz/slim3-contoller
* update README - installation/ usage notes
* package up controller lib (slim3-controller)
tests
* can we run tests with run() instead? then we can use App for bootstrap (eg. routes)
* crud: update, delete
* query
* more appropriate error messages for TestCase's assert* methods
* validate json

martynbiz/php-mongo
* does save() return true or false?
* paginate - test limit/skip in integrated
* access properties like: $user['username'] or $user->username
* write description for packagist
* SO is it better to update few name/values with $set, or just overwrite all with $this->data?
* test Mongo::update()
* get human readable date
* allow us to insert an empty draft
