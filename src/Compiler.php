<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc;

use Pharc\Project\Target;
use Pharc\Project\Target\Dependency\DependencyManagerFactory;

/**
 * Class Compiler
 * @package Pharc
 */
class Compiler
{
    /**
     * @var DependencyManagerFactory
     */
    protected $dependencyManagerFactory;

    /**
     * @param Target $target
     */
    public function compile(Target $target)
    {
        $signatureMethod = Target::$SIGNATURE_METHODS[$target->getSignatureMethod()];

        $pharFile = new PharFile($target->getPhar(), getcwd(), $signatureMethod);
        $pharFile->startBuffering();

        // add file group
        foreach ($target->getFileGroups() as $fileGroup) {
            $pharFile->addFileGroup($fileGroup);
        }

        $dependencyManager = $this->getDependencyManagerFactory()
            ->create('composer', $target->getComposer());
        $dependencyFileGroup = $dependencyManager->build();

        $pharFile->addFileGroup($dependencyFileGroup);

        // add the bin file.
        $binPreProcessor = new RegexPreProcessor();
        $binPreProcessor->setFind('{^#!/usr/bin/env php\s*}')
            ->setReplace('');
        $pharFile->addFile(
            new \SplFileInfo($target->getBin()),
            [
                $binPreProcessor,
            ],
            false
        );

        $pharFile->setStub($target->getStub());

        $pharFile->stopBuffering();

        $pharFile->addFile(
            new \SplFileInfo($target->getLicense()),
            [],
            false,
            PHP_EOL
        );

        unset($pharFile);

    }

    /**
     * @return DependencyManagerFactory
     */
    public function getDependencyManagerFactory()
    {
        if ($this->dependencyManagerFactory === null) {
            $this->dependencyManagerFactory = new DependencyManagerFactory();
        }
        return $this->dependencyManagerFactory;
    }

    /**
     * @param DependencyManagerFactory $dependencyManagerFactory
     * @return static
     */
    public function setDependencyManagerFactory($dependencyManagerFactory)
    {
        $this->dependencyManagerFactory = $dependencyManagerFactory;
        return $this;
    }
}