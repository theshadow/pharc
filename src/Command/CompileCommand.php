<?php
/**
 * Short description
 * 
 * Long Description
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

namespace Pharc\Command;

use Pharc\Compiler\ProjectFileLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CompileCommand extends Command
{
    /**
     *
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
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $outputInterface
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $outputInterface)
    {
        $cwd = getcwd();
        $projectFile = $cwd . '/pharc.yml';

        $fileLoader = new ProjectFileLoader();

        try {
            $projectFileData = $fileLoader->load($projectFile);
        } catch (\InvalidArgumentException $e) {
            $outputInterface->writeln('<error>Invalid file path "' . $projectFile . '".</error>');
        } catch (\LogicException $e) {
            $outputInterface->writeln('<error>Unable to read file "' . $projectFile . '".</error>');
        }

        $outputInterface->writeln(var_export($projectFileData, true));
    }
}