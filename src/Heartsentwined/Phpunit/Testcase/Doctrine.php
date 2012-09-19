<?php
namespace Heartsentwined\Phpunit\Testcase;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;
use Heartsentwined\FileSystemManager\FileSystemManager;

class Doctrine extends Zf
{
    /**
     * sets
     * - $this->em      Doctrine\ORM\EntityManager
     * - $this->tmpDir  (path to tmp dir)
     *
     * @param string $bootstrap path to bootstrap file
     *      bootstrap file should return Zend\Mvc\Application
     * @param string $em ServiceManager alias of EntityManager
     * @param string $tmpDir path to tmp dir
     * @return void
     */
    public function setUp($bootstrap, $em, $tmpDir)
    {
        parent::setup($bootstrap);
        $this->em = $sm->get($em);
        $this->tmpDir = $tmpDir;

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
