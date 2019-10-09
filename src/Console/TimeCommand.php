<?php 
namespace Corm\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class TimeCommand  extends SymfonyCommand
{
 
    public function __construct()
    {
        parent::__construct();
    }
    

    public function configure()
    {
        $this -> setName('greet')
            -> setDescription('Greet a user based on the time of the day.')
            -> setHelp('This command allows you to greet a user based on the time of the day...')
            -> addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this -> greetUser($input, $output);
    }

    protected function greetUser(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output -> writeln([
            '====**** User Greetings Console App ****====',
            '==========================================',
            '',
        ]);
        
        // outputs a message without adding a "\n" at the end of the line
        $output -> write($this -> getGreeting() .', '. $input -> getArgument('username'));
    }
    private function getGreeting()
    {
        
    }
}