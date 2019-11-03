<?php

namespace Corm\Builders;

use Corm\Exceptions\BadParametersException;
use Corm\Exceptions\ClassNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;
use Example\Database\ExampleDB;
use phpDocumentor\Reflection\DocBlockFactory;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\PhpFile;
use Corm\Parser;
use Corm\Models\DBClassModel;
use Corm\Models\DaoGetter;
use Corm\Builders\DBClassImplBuilder;
use Corm\Builders\DaoClassImplBuilder;
class Builder
{

    private $parser;

    public function __construct()
    {
        $this->parser =  new Parser();
    }

    public function build($codeDir, $dbClass)
    {
        $dbClassInfo = $this->parser->parseDatabaseClass($dbClass);
        $dBclassBuilder  = new DBClassImplBuilder($codeDir);
        $dBclassBuilder->build($dbClassInfo);

        $entities = $dbClassInfo->getEntitiesMap();

        $daoBuilder = new DaoClassImplBuilder($codeDir, $entities);
       
        foreach($dbClassInfo->daoInterfaces as $dao){
            $daoClassInfo = $this->parser->parseDaoClass($dao->returnType);
            $daoBuilder->build($daoClassInfo);
        }
    }
}
