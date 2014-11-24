<?php

namespace Application\Bundle\FrontBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;




class ExportReportCommand extends ContainerAwareCommand
{

	protected function configure()
	{
		$this
		->setName('avcc:export-report')
		->setDescription('Export the Records that are in queue and email to user.')
		->addArgument(
		'name', InputArgument::OPTIONAL, 'Who do you want to greet?'
		)
		->addOption(
		'yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters'
		)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{


		 $em = $this->get('doctrine')->getEntityManager('default');
		$name = $input->getArgument('name');
		if ($name)
		{
			$text = 'Hello ' . $name;
		}
		else
		{
			$text = 'Hello';
		}

		if ($input->getOption('yell'))
		{
			$text = strtoupper($text);
		}

		$output->writeln($text);
	}

}
