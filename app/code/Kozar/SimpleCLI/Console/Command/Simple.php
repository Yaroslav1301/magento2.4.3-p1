<?php

namespace Kozar\SimpleCLI\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Simple extends Command
{
    const OPTION_PARAM = 'name';

    const ARGUMENT_PARAM = 'age';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('simple:first:command');
        $this->setDescription('This is my first simple console command.');
        $this->addOption(
            self::OPTION_PARAM,
            'N',
            InputOption::VALUE_REQUIRED,
            'Name'
        );
        $this->addArgument(
            self::ARGUMENT_PARAM,
            InputOption::VALUE_OPTIONAL,
            'Age'
        );

        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*
         * You can provide here your custom business logic
         * I have created this class to clarify how to us options and arguments
         */
        if ($name = $input->getOption(self::OPTION_PARAM)) {
            $output->writeln('<info>Provided name is `' . $name . '`</info>');
            if ($age = $input->getArgument(self::ARGUMENT_PARAM)) {
                $age = array_shift($age);
                $output->writeln('<info>Provided age is `' . $age . '`</info>');
            }
            $output->writeln('<comment>Everething done correctly.</comment>');
        } else {
            $output->writeln('<error>Please provide a name option</error>');
            $output->writeln('<comment>Please run command againg with --name [param].</comment>');
        }
    }
}
