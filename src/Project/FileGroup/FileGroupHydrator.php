<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Project\FileGroup;

use Pharc\Project\FileGroupInterface;

/**
 * Class FileSetHydrator
 * @package Pharc\Project\FileSet
 */
class FileGroupHydrator
{
    /**
     * @var
     */
    protected $preProcessFactory;

    /**
     * @param FileGroupInterface $fileSet
     * @param array $data
     * @return FileGroupInterface
     */
    public function hydrate(FileGroupInterface $fileSet, array $data)
    {
        $fileSet->setNames($data['names']);

        if (isset($data['excluded-names'])) {
            $fileSet->setExcludedNames($data['excluded-names']);
        }

        if (isset($data['ignore-vcs'])) {
            $fileSet->setIgnoreVcs((bool)$data['ignore-vcs']);
        }

        if (isset($data['strip'])) {
            $fileSet->setStrip((bool)$data['strip']);
        }

        if (isset($data['spacer'])) {
            $fileSet->setSpacer($data['spacer']);
        }

        if (isset($data['exclude'])) {
            $fileSet->setExcludes($data['exclude']);
        }

        if (isset($data['preprocess'])) {
            foreach ($data['preprocess'] as $name => $preprocessData) {
                $preprocessor = $this->getPreProcessorFactory()->create($preprocessData);
                $fileSet->addPreprocessor($name, $preprocessor);
            }
        }

        if (isset($data['paths'])) {
            $paths = !is_array($data['paths']) ? array($data['paths']) : $data['paths'];
            $fileSet->setPaths($paths);
        }
    }

    /**
     * @return PreProcessorFactory
     */
    public function getPreProcessorFactory()
    {
        if (is_null($this->preProcessFactory)) {
            $this->preProcessFactory = new PreProcessorFactory();
        }
        return $this->preProcessFactory;
    }
}