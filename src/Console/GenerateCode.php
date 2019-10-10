<?php

namespace Workshop\Console;

use EventSauce\EventSourcing\CodeGeneration\CodeDumper;
use EventSauce\EventSourcing\CodeGeneration\YamlDefinitionLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateCode
 *
 * @package Workshop\Console
 */
class GenerateCode extends Command
{
    protected static $defaultName = 'workshop:generate-code';

    protected function configure()
    {
        $this
            ->setDescription('Generates EventSauce code files from YAML definition file')
            ->setHelp('This command allows you to generate EventSauce code files from a YAML definition file')
            ->addArgument('yaml', InputArgument::REQUIRED, 'YAML definition file to use')
            ->addArgument('destination', InputArgument::REQUIRED, 'Destination path and file name for the generated code');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $yamlPath = $input->getArgument('yaml');
        $destination = $input->getArgument('destination');

        $output->writeln($yamlPath);

        $loader = new YamlDefinitionLoader();
        $dumper = new CodeDumper();
        $phpCode = $dumper->dump($loader->load($yamlPath));
        file_put_contents($destination, $phpCode);
    }
}
