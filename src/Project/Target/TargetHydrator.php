<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\Target;

use Pharc\Project\Target;
use Pharc\Project\Target\Stub\DefaultStub;
use Pharc\Project\Target\FileGroup\FileGroupHydrator;
use Pharc\Project\Target\Composer;
use Pharc\Project\Target\Composer\ComposerHydrator;

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
            'composer' => $target->getComposer(),
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
        if (isset($data['stub'])) {
            $stub = $this->getStubFactory()->create($data['stub']);

        } else {
            $stub = new DefaultStub();
            $stub->setBin($target->getBin())
                ->setPhar($target->getPhar());
        }

        $target->setStub($stub);


        $composer = $this->getComposerHydrator()->hydrate(new Composer(), $data['composer']);

        $target->setComposer($composer);

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
}