<?php
namespace Heartsentwined\Phpunit\Testcase;

/**
 * Zf application + service manager setup
 *
 * @author heartsentwined <heartsentwined@cogito-lab.com>
 * @license GPL http://opensource.org/licenses/gpl-license.php
 */
class Zf extends \PHPUnit_Framework_TestCase
{
    /**
     * sets
     * - $this->application Zend\Mvc\Application
     * - $this->sm          Zend\ServiceManager\ServiceManager
     *
     * @param string $bootstrap path to bootstrap file
     *      bootstrap file should return Zend\Mvc\Application
     * @return void
     */
    public function setUp($bootstrap)
    {
        $application = require $bootstrap;
        $this->application = $application;
        $this->sm = $application->getServiceManager();
    }

    public function testServiceManagerInstance()
    {
        $this->assertInstanceOf(
            'Zend\ServiceManager\ServiceManager',
            $this->sm);
    }
}
