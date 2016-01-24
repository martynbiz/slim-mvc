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

martynbiz/martynbiz
* photo uploads - hash storage dirs, resize, 

* no duplicate email register
* paginate - test limit/skip in integrated
* style login form, register form
* confirm alert when deleting
* portfolio
* contact form
* about me
* search

* homepage - latest
* csrf - ask question on SO

v2
sms links

test
* validate role types in admin/users, setting roles,
* integrated test for tags MongoIterator -> array(DBRefs)
* test forward on admin/user/edit and admin/tags/edit&create
* edit/delete/show throw exception on nonfound (users, article, tags)
* tags: tags are being saved (submit, approve, draft)

bug: flash message not persisting across redirect?
BUG!!! delete one is deleting ALL :O

* register - no duplicate emails, username

buy wordup.com


* file uploads


* Maybe we don't need to pass in $container stuff after all http://stackoverflow.com/questions/34839399/how-to-access-the-container-within-middleware-class-in-slim-v3/34930473#34930473

testing alternatives - admin/articles with acl method calls mocked, register is setting id/_id?, setting role on register,
  auto signin after register,
* codeception
* mockery
* phpspec

martynbiz\localmap
* route /{region}/{id}/{slug} -- on the fly routes (regions collection), getUrl() eg. /glasgow/123/green-shop
* route /{tag}
* map - lat/lng, location box on form
* path_for all links
* pagination? $this->service('model.articles')->find(...)->paginate(20);


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





martynbiz/slim3-contoller
* can we run tests with run() instead? then we can use App for bootstrap (eg. routes)
* query
* more appropriate error messages for TestCase's assert* methods
* validate json
* assertViewReceives

martynbiz/php-mongo
* onSave() -- generate slug
* does save() return true or false?
* paginate - test limit/skip in integrated
* access properties like: $user['username'] or $user->username
* SO is it better to update few name/values with $set, or just overwrite all with $this->data?
* get human readable date
* allow us to insert an empty draft
