<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Command;

use Exception;
use LogicException;
use InvalidArgumentException;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use RomaricDrigon\MetaYaml\MetaYaml;

use Pharc\Compiler;
use Pharc\Project\BuildFileLoader;
use Pharc\Project\BuildFileParser;
use Pharc\PharFile;
use Pharc\Project\Target\Composer\ConfigFileLoader;
use Pharc\Project\FileGroup\PreProcessor\RegexPreProcessor;
use Pharc\Project\Target;

use Seld\PharUtils\Timestamps;

class CompileCommand extends Command
{
    /**
     * @var BuildFileLoader
     */
    protected $buildFileLoader;

    /**
     * @var MetaYaml
     */
    protected $metaYaml;

    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @var ConfigFileLoader
     */
    protected $composerConfigFileLoader;

    /**
     * Configures the command.
     */
    public function configure()
    {
        $this->setName('compile')
            ->setDescription('Compile a PHP application into a PHAR')
            ->addOption(
                'pharc-file',
                null,
                InputOption::VALUE_REQUIRED,
                'By default pharc looks for a pharc.yml in the current working directory you may instead specify one.'
            )
            ->addArgument(
                'target',
                null,
                'The target to compile',
                'default'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $outputInterface
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $outputInterface)
    {
        $target = $input->getArgument('target');
        $cwd = getcwd();
        $buildFile = $cwd . '/pharc.yml';

        try {
            $buildFileData = $this->getBuildFileLoader()->load($buildFile);
        } catch (InvalidArgumentException $e) {
            $outputInterface->writeln('<error>Invalid file path "' . $buildFile . '".</error>');
            return;
        } catch (LogicException $e) {
            $outputInterface->writeln('<error>Unable to read file "' . $buildFile . '".</error>');
            return;
        }

        try {
            $this->getMetaYaml()->validate($buildFileData);
        } catch(Exception $e) {
            $outputInterface->writeln('<error>Unable to validate pharc.yml schema: ' . $e->getMessage() . '</error>');
            return;
        }

        $buildFileParser = new BuildFileParser();
        $project = $buildFileParser->parse($buildFileData);

        if (!$project->hasTarget($target)) {
            $outputInterface->writeln('<error>The target ' . $target . ' does not exist</error>');
        }

        $target = $project->getTarget($target);

        $signatureMethod = Target::$SIGNATURE_METHODS[$target->getSignatureMethod()];

        $pharFile = new PharFile($target->getPhar(), $cwd, $signatureMethod);
        $pharFile->startBuffering();

        // add file group
        foreach ($target->getFileGroups() as $fileGroup) {
            $pharFile->addFileGroup($fileGroup);
        }

        $dependencyManager = $target->getDependencyManager();

        // should I use the null object pattern here?
        if (!is_null($dependencyManager)) {
            $pharFile->addFileGroup($dependencyManager->build());
        }

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

        //todo: implement signing
    }

    /**
     * @return MetaYaml
     */
    public function getMetaYaml()
    {
        if (is_null($this->metaYaml)) {
            $this->metaYaml = new MetaYaml(
                $this->loadSchemaFile(),
                true
            );
        }
        return $this->metaYaml;
    }

    /**
     * @return BuildFileLoader
     */
    public function getBuildFileLoader()
    {
        if (is_null($this->buildFileLoader)) {
            $this->buildFileLoader = new BuildFileLoader();
        }

        return $this->buildFileLoader;
    }

    public function getComposerConfigFileLoader()
    {
        if (is_null($this->composerConfigFileLoader)) {
            $this->composerConfigFileLoader = new ConfigFileLoader();
        }
        return $this->composerConfigFileLoader;
    }

    protected function loadSchemaFile()
    {
        return Yaml::parse(
            file_get_contents(__DIR__ . '/../../res/schema.yml')
        );
    }

    /**
     * @return Compiler
     */
    protected function getCompiler()
    {
        if (is_null($this->compiler)) {
            $this->compiler = new Compiler();
        }

        return $this->compiler;
    }
}