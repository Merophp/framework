# Introduction

Merophp is a mini framework for web applications. It is mainly designed as a tool for 
refactoring of legacy projects. This means you can integrate it and don't have to instantly do a big migration. 
Instead, you can do the migration over a long period of time.

It implements following standards:
* psr-4
* psr-7
* psr-12
* psr-15
* psr-16
* psr-17

It provides:
* a router
* an PSR-4 autoloader
* some cache classes
* a HTTP handling
* a bundle management
* object management with dependency injection
* an event manager
* a simple view engine

## Installation

Via composer:

<code>
composer require merophp/framework
</code>

## Basic Usage

<pre><code>use Merophp\Framework\AppFactory;
use Merophp\Router\Routes\GetRoute;

require_once dirname(__DIR__).'/vendor/autoload.php';

//Create the app object
$app = AppFactory::create();

//Add routes to the router
$app->getRouter()->addRoutes(
    new GetRoute('/hello-world', function($request, $response){
        $response->getBody()->write('Hello World');
        return $response;
    })
);

//Run Forrest, run!
$app->start();
</code></pre>

This is all you need to create a hello world app.

### The Bundle Concept

You can use bundles aka plugins to structure and flexibilize your application. 
Merophp comes with a bundle interface for manipulation and extending of the request handling.
Bundles should be separate composer packages to use composers autoloading and dependency management.
Your bundles has to provide a bootstrapper class which implements the interface <i>Merophp\BundleManager\BundleBootstrapper\BundleBootstrapperInterface</i> or extending the <i>Merophp\Framework\BundleManagement\BundleBootstrapper\AbstractBundleBootstrapper</i> class.

{your bundle path}/src/Bootstrapping/Bootstrapper.php:
<pre><code>namespace MyVendor\MyBundle\Bootstrapping;

use Merophp\Framework\BundleManagement\BundleBootstrapper\AbstractBundleBootstrapper;
use Merophp\Router\Routes\GetRoute;

class Bootstrapper extends AbstractBundleBootstrapper
{
    public function setup(){
        $this->app->getRouter()->addRoutes(
            new GetRoute('/hello-world', function($request, $response){
                $response->getBody()->write('Hallo World');
                return $response;
            })
        );
    }
    public function tearDown(){}
}
</code></pre>

{your bundle path}/composer.json:
<pre><code>{
  "name": "my-vendor/my-bundle",
  "autoload": {
    "psr-4": {"MyVendor\\MyBundle\\": "src/"}
  },
  "require": {
	"merophp/framework":"*"
  }
}
</code></pre>

Run: <code>
composer update
</code>

index.php:
<pre><code>use Merophp\Framework\AppFactory;

require_once dirname(__DIR__).'/vendor/autoload.php';

//Create the app object
$app = AppFactory::create();

//Register bundle
$app->registerBundle('MyVendor\\MyBundle');

//Run Forrest, run!
$app->start();
</code></pre>

You can also register bundles inside the setup method of a bundles' bootstrapper. 

### Using Controller Classes

In most cases you do not want to add a callback function to a route and use a controller with actions instead.
Merophp provides you an easy-to-use controller integration:

Adding a route:
<pre><code>use MyVendor\MyBundle\Controller\MyCoolController;

$this->app->getRouter()->addRoutes(
     new GetRoute('/hello/{with-name}', [MyCoolController::class, 'myAction'])
);
</code></pre>

{your bundle path}/src/Controller/MyCoolController.php
<pre><code>namespace MyVendor\MyBundle\Controller;

use Merophp\Framework\RequestControlling\AbstractController;

class MyCoolController extends AbstractController
{
    /**
     * @param string $name The param from the url
     */
    public function myAction($name)
    {
        //$arguments = $this->request->getDecodedBody();

        $this->view->text('Hello '.$name);
    }
}
</code></pre>

Sending an HTTP request with url https://{your-address}/hello/Martin will give you the response 'Hello Martin'.

You also can use dependency injection inside your controller, manipulate the response object and store information in the TransactionCache for the next request of the visitor.

## About

### Requirements

Merophp Framework works with PHP 7.4, 8.0 and 8.1. No further PHP extensions required.

### Support

Be aware, this is something like a just-for-fun project and there is no guaranty of support or further maintenance. 
There is also a high risk of volatility.
