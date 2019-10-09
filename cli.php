#!/usr/bin/env php


<?php

require_once __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Console\Application;
use Corm\Console\TimeCommand;

const DB_NAMESPACE =  "Example";

use Example\ExampleDB;


$app = new Application();
$app -> add(new TimeCommand());
$app -> run();
