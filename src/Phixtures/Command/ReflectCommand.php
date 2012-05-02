<?php

namespace Phixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Phixtures\DIContainer;

class ReflectCommand extends Command
{

	protected $_container;

	public function __construct(DIContainer $container, $name = null)
	{
		$this->_container = $container;
		parent::__construct($name);
	}

	protected function configure()
	{
		$this->setName('reflect')
			->setDescription('Reflect the tables in the target schema and build base fixture classes')
			
			->addOption(
				'target-schema',
				's',
				InputOption::VALUE_OPTIONAL,
				'the schema to reflect',
				$this->_container['database.target_schema']
			)
			->addOption(
				'tables',
				't',
				InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
				'specific tables to reflect *only*',
				null
			)
			->addOption(
				'output-dir',
				'd',
				InputOption::VALUE_OPTIONAL,
				'the directory to write generated fixture classes',
				$this->_container['fixtures.target_directory']
			)
			->addOption(
				'like-a-boss',
				'c',
				InputOption::VALUE_NONE,
				'automatically confirm and don\'t back up previous fixture classes'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$targetSchema = $input->getOption('target-schema');
		if ($targetSchema !== $this->_container['database.target_schema']) {
			$this->_container['database.target_schema'] = $targetSchema;
		}

		$schema = $this->_container['database.schema'];
		$schema->reflect();
		$targetDirectory = $input->getOption('output-dir');
		if (!file_exists($targetDirectory) && is_writeable(dirname($targetDirectory))) {
			mkdir($targetDirectory, 0755, true);
		}
		$basePath = realpath($targetDirectory);
		$warnings = array();
		foreach ($schema as $table) {
			$warnings[$table->getName()] = array();
			$table->buildBaseClass();
			$path = $basePath . DIRECTORY_SEPARATOR . $table->getBaseClassName() . '.php';
			$output->writeln(sprintf('<info>Writing to %s </info>', $path)); 
			file_put_contents($path, $table->getBaseClass());

			foreach ($table as $column => $details) {
				if (false === $details['nullable'] && null === $details['default']) {
					$warnings[$table->getName()][] = $column;
				}
			}
		}

		foreach ($warnings as $table => $columns) {
			foreach ($columns as $column) {
				$output->writeln(sprintf("<comment>%s.%s is defined as not null and has no default value, you should add a default value to the fixture class.</comment>",
					$table, $column));
			}
		}

	}

}
