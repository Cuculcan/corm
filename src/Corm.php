<?php

namespace Corm;

use Symfony\Component\Console\Output\OutputInterface;
use Example\ExampleDB;

class Corm
{

    /**
     * @var string 
     */
    private $namespace;

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    public function generate(string $dbName, OutputInterface $output)
    {

        $output->write(['start generator', "\n"]);

        $dbClass = "\\" . $this->namespace . "\\" . $dbName;
        $output->write([$dbClass, "\n"]);


        $dbClassRef = new \ReflectionClass($dbClass);

        $output->write([$dbClassRef->getDocComment()]);
        return true;
    }
}
