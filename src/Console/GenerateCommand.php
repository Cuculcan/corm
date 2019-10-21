<?php

namespace Corm\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Corm\Corm;

class GenerateCommand  extends SymfonyCommand
{
    private $namespace = "";

    public function __construct($ns)
    {
        parent::__construct();
        $this->namespace = $ns;
    }


    public function configure()
    {
        $this->setName('generate')
            ->setDescription('generate db impl.')
            ->setHelp('generate db impl');
        //-> addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generate($input, $output);
    }

    protected function generate(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            ' prepare ... ',
        ]);

    }
}
