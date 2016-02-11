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

Laravel Blade

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


metroworks/jtsso
* package up skeleton app from martynbiz/martynbiz
*

martynbiz/martynbiz

* add featured option to articles
* add photos to articles
* list article on homepage
* show frontend article
* list articles on tag page
* tidy up content: p tags, headers, etc
* just combine submit/approve/update

* when deleting a tag, remove from all articles
* when deleting a user, what to do with articles?
* when deleting an article, anything?

import wordpress


nesbot/carbon
add @section('title')

* paginate - test limit/skip in integrated



* disable registration

v２
* languages - japanese and english
* put the $router in the view for $router->pathFor('index_index')
* sms links
* export data



tidyups
* status buttons in article::edit shoudl be constants
* terms - publish

？ create static pages? like wordpress does
  /pages/{slug} -> PagesController::show($id);

  $router->pathFor('pages_show', 'winter-2016-offer'); // e.g. /pages/winter-2016-offer

  when the page is created, the link name cannot be changed


* no duplicate email register
* generate username
* welcome email, remember me
* facebook login
* saml?


* portfolio
* about me
* contact form

* search
* fix titles on pages esp homepage
* csrf - ask question on SO

v2
* video uploads





test
* validating images
* User::encryptPassword -- should return null, and try password_verify
* validate role types in admin/users, setting roles,
* integrated test for tags MongoIterator -> array(DBRefs)
* test forward on admin/user/edit and admin/tags/edit&create
* edit/delete/show throw exception on nonfound (users, article, tags)
* tags: tags are being saved (submit, approve, draft)
* getTagsFromTagIds, attachPhotos, class TestArticleProtected { public function testAttachPhotos() { return $this->attachPhotos } }
* uploading images
* utils slugify
* article methods - getPublishedAt, save
* article::has

BUG!!! delete one is deleting ALL :O
* confirm alert when deleting
bug: why is import still duplicate tags with array_unique




* Maybe we don't need to pass in $container stuff after all http://stackoverflow.com/questions/34839399/how-to-access-the-container-within-middleware-class-in-slim-v3/34930473#34930473

testing alternatives - admin/articles with acl method calls mocked, register is setting id/_id?, setting role on register,
  auto signin after register,
* codeception
* mockery
* phpspec




* create project that can easily be installed (martynbiz/slim3-project)

* package up library (martynbiz/wordup-cms)
martynbiz\localmap
* route /{region}/{id}/{slug} -- on the fly routes (regions collection), getUrl() eg. /glasgow/123/green-shop
* route /{tag}
* map - lat/lng, location box on form
* path_for all links
* pagination? $this->service('model.articles')->find(...)->paginate(20);


v2
* contributers/ submit articles for approval
* homepage - featured, latest
* photos (dropzone)
* video -- youtube uploads
* vagrant
* zend-like: bootstrap, resources (one for mongo, another for eloquent), -- __construct?
* popular - google analytics
* graphs homepage


vx
* alloyeditor - edit in place
* default action -> views (e.g. members -> MembersController::index / members/index.html)



martynbiz/php-validator
* isNotEmpty returns true when ?

martynbiz/slim3-contoller
* can we run tests with run() instead? then we can use App for bootstrap (eg. routes)
* query
* more appropriate error messages for TestCase's assert* methods
* validate json
* assertViewReceives

martynbiz/php-mongo
* access properties like: $article['title'], $article['featured'], $article['page_views']
* does save() return true or false?
* paginate - test limit/skip in integrated
* SO is it better to update few name/values with $set, or just overwrite all with $this->data?
* allow us to insert an empty draft
