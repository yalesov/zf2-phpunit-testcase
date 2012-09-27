<?php
namespace Heartsentwined\Phpunit\Testcase;

use Heartsentwined\ArgValidator\ArgValidator;

/**
 * basic ZF application boostrap: setup application + service manager
 *
 * @author heartsentwined <heartsentwined@cogito-lab.com>
 * @license GPL http://opensource.org/licenses/gpl-license.php
 */
abstract class Zf extends \PHPUnit_Framework_TestCase
{
    /**
     * path to bootstrap file, which should return Zend\Mvc\Application
     *
     * @var string
     */
    protected $bootstrap;
    protected function setBootstrap($bootstrap)
    {
        ArgValidator::assert($bootstrap, array('string', 'min' => 1));
        $this->bootstrap = $bootstrap;

        return $this;
    }

    /**
     * ZF application instance
     *
     * @var Zend\Mvc\Application
     */
    protected $application;

    /**
     * ZF service manager instance
     *
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    /**
     * setup ZF application and service manager
     * - $this->application Zend\Mvc\Application
     * - $this->sm          Zend\ServiceManager\ServiceManager
     *
     * @return void
     */
    public function setUp()
    {
        $application = require $this->bootstrap;
        $this->application = $application;
        $this->sm = $application->getServiceManager();
    }

    /**
     * start afresh
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->application);
        unset($this->sm);
    }

    public function testServiceManagerInstance()
    {
        $this->assertInstanceOf(
            'Zend\ServiceManager\ServiceManager',
            $this->sm);
    }
}
