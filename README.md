# zf2-phpunit-testcase

[PHPUnit](http://phpunit.de/) test case base classes for ZF2 applications.

# Installation

[Composer](http://getcomposer.org/):

```json
{
    "require": {
        "heartsentwined/zf2-phpunit-testcase": "1.*"
    }
}
```

[PHPUnit](http://phpunit.de/) is **not** bundled with this package. You can install it through PEAR and use the CLI at `phpunit`:

```sh
$ pear config-set auto_discover 1
$ pear install pear.phpunit.de/PHPUnit
```

# Usage

First, you will need a bootstrap file which returns  `Zend\Mvc\Application`. Here is an example:

```php
use Zend\Mvc\Application;
chdir(__DIR__); // chdir() to application root
require 'vendor/autoload.php';
/*
 * why another set of config?
 * now that you're writing unit test
 * odds are that you would want to override some production settings here,
 * e.g. load only certain module; use a test database connection, etc
 */
$config = array(
    'modules'   => array(
        'Foo',
        'Bar',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            __DIR__ . '/test/config/{,*.}php'
        ),
        'module_paths' => array(
            'Foo' => __DIR__,
            'vendor',
        ),
    ),
));
return Application::init($config);
```

Now, on to the test case base classes:

## Zf

Bootstrap your Zf2 application with the bootstrap file `foo/bootstrap.php`:

```php
use Heartsentwined\Phpunit\Testcase\Zf as ZfTestcase;

class FooTest extends ZfTestcase
{
    public function setUp()
    {
        $this->setBootstrap('foo/bootstrap.php'); // watch out for relative dirs! - use __DIR__ if needed
        parent::setUp();
    }

    public function tearDown()
    {
        // your tearDown() operations
        parent::tearDown();
    }

    public function testFoo()
    {
        // $this->application instance of Zend\Mvc\Application
        // $this->sm instance of Zend\ServiceManager\ServiceManager
    }
}
```

## Doctrine

Bootstrap your Zf2 application with the bootstrap file `foo/bootstrap.php`, and with [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html) support. Your `EntityManager` is aliased with the ServiceManager at `doctrine.entitymanager.orm_default`.

(Optional) You have declared the directory `foo/tmp` as a temporary directory somewhere in your config files - possibly for storing Proxies, and you want this directory to be created before each test; and deleted after each test. (i.e. during setUp() and teardown())

```php
use Heartsentwined\Phpunit\Testcase\Doctrine as DoctrineTestcase;

class FooTest extends DoctrineTestcase
{
    public function setUp()
    {
        // fluent interface available
        $this
            ->setBootstrap('foo/bootstrap.php')
            ->setEmAlias('doctrine.entitymanager.orm_default')
            ->setTmpDir('foo/tmp'); // optional: see use case above
        parent::setUp();
    }

    public function tearDown()
    {
        // your tearDown() operations
        parent::tearDown();
    }

    public function testFoo()
    {
        // $this->application instance of Zend\Mvc\Application
        // $this->sm instance of Zend\ServiceManager\ServiceManager
        // $this->em instance of Doctrine\ORM\EntityManager
        // $this->tmpDir = 'foo/tmp'
    }
}
```
