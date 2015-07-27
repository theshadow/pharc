<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target;

use LogicException;

use Pharc\Project\Target;
use Pharc\Project\Target\Stub\DefaultStub;
use Pharc\Project\Target\FileGroup\FileGroupHydrator;
use Pharc\Project\Target\Composer;
use Pharc\Project\Target\Composer\ComposerHydrator;
use Pharc\Project\Target\Dependency\DependencyManagerFactory;

/**
 * Class ProjectHydrator
 * @package Pharc\Project
 */
class TargetHydrator
{
    /**
     * @var StubFactory
     */
    protected $stubFactory;

    /**
     * @var ComposerHydrator
     */
    protected $composerHydrator;

    /**
     * @var FileGroupHydrator
     */
    protected $fileGroupHydrator;

    /**
     * @var DependencyManagerFactory
     */
    protected $dependencyManagerFactory;

    /**
     * @param Target $target
     * @return array
     */
    public function extract(Target $target)
    {
        $result = [
            'phar' => $target->getPhar(),
            'stub' => $target->getStub(),
            'license' => $target->getLicense(),
            'signature-method' => $target->getSignatureMethod(),
            //'composer' => $target->getComposer(),
            'files' => $target->getFileGroups(),
        ];

        return $result;
    }

    /**
     * @param Target $target
     * @param array $data
     * @return Target
     */
    public function hydrate(Target $target, array $data)
    {
        $target->setPhar($data['phar'])
            ->setBin($data['bin'])
            ->setLicense($data['license'])
            ->setSignatureMethod($data['signature-method']);

        // if no default stub is defined then we need a default one
        $stub = new DefaultStub();
        $stub->setBin($target->getBin())
            ->setPhar($target->getPhar());

        if (isset($data['stub'])) {
            $stub = $this->getStubFactory()->create($data['stub']);
        }

        $target->setStub($stub);

        $dependencyType = null;
        if (isset($data['dependencies'])) {
            $dependencyType = $data['dependencies'];
        }

        $dependencyConfig = null;
        if (isset($data[$dependencyType])) {
            $dependencyConfig = $data[$dependencyType];
        }

        if (count($dependencyConfig) == 0) {
            throw new LogicException(
                sprintf("Dependency manager '%s' was defined but no configuration section found.", $dependencyType)
            );
        }

        $dependencyManager = $this->getDependencyManagerFactory()
            ->create($dependencyType, $dependencyConfig);

        $target->setDependencyManager($dependencyManager);

        foreach ($data['files'] as $name => $fileGroup) {
            $fileGroup['name'] = $name;
            $files = $this->getFileGroupHydrator()->hydrate(new FileGroup(), $fileGroup);
            $target->addFiles($name, $files);
        }

        return $target;
    }

    /**
     * @return ComposerHydrator
     */
    public function getComposerHydrator()
    {
        if (is_null($this->composerHydrator)) {
            $this->composerHydrator = new ComposerHydrator();
        }

        return $this->composerHydrator;
    }

    /**
     * @return StubFactory
     */
    public function getStubFactory()
    {
        if (is_null($this->stubFactory)) {
            $this->stubFactory = new StubFactory();
        }
        return $this->stubFactory;
    }

    /**
     * @return FileGroupHydrator
     */
    public function getFileGroupHydrator()
    {
        if (is_null($this->fileGroupHydrator)) {
            $this->fileGroupHydrator = new FileGroupHydrator();
        }
        return $this->fileGroupHydrator;
    }

    /**
     * @return DependencyManagerFactory
     */
    public function getDependencyManagerFactory()
    {
        if (is_null($this->dependencyManagerFactory)) {
            $this->dependencyManagerFactory = new DependencyManagerFactory();
        }
        return $this->dependencyManagerFactory;
    }

    /**
     * @param DependencyManagerFactory $managerFactory
     * @return static
     */
    public function setDependencyManagerFactory(DependencyManagerFactory $managerFactory)
    {
        $this->dependencyManagerFactory = $managerFactory;
        return $this;
    }
}