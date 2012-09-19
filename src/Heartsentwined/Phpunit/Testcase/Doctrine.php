<?php
namespace Heartsentwined\Phpunit\Testcase;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;
use Heartsentwined\ArgValidator\ArgValidator;
use Heartsentwined\FileSystemManager\FileSystemManager;

/**
 * Doctrine ORM setup: set EntityManager and proxy tmp dir
 *
 * @author heartsentwined <heartsentwined@cogito-lab.com>
 * @license GPL http://opensource.org/licenses/gpl-license.php
 */
class Doctrine extends Zf
{
    /**
     * ServiceManager alias of EntityManager
     *
     * @var string
     */
    protected $emAlias;
    protected function setEmAlias($emAlias)
    {
        ArgValidator::assert($emAlias, array('string', 'min' => 1));
        $this->emAlias = $emAlias;
        return $this;
    }

    /**
     * the ORM EntityManager
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * path to tmp dir, possibly for storing proxies
     *
     * @var string
     */
    protected $tmpDir;
    protected function setTmpDir($tmpDir)
    {
        ArgValidator::assert($tmpDir, array('string', 'min' => 1));
        $this->tmpDir = $tmpDir;
        return $this;
    }

    /**
     * setup EntityManager and tmp dir
     *
     * @return void
     */
    public function setUp()
    {
        parent::setup();

        $this->em = $this->sm->get($this->emAlias);
        if (!is_dir($this->tmpDir)) mkdir($this->tmpDir);

        $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        if (!empty($metadatas)) {
            $tool = new SchemaTool($this->em);
            $tool->createSchema($metadatas);
        } else {
            throw new SchemaException(
                'No metadata classes to process'
            );
        }
    }

    /**
     * remove tmp dir
     *
     * @return void
     */
    public function tearDown()
    {
        FileSystemManager::rrmdir($this->tmpDir);
    }

    public function testEmInstance()
    {
        $this->assertInstanceOf(
            'Doctrine\ORM\EntityManager',
            $this->em);
    }
}
