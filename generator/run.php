#!/usr/bin/env php
<?php
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'wget https://getcomposer.org/composer.phar' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/autoload.php';

$fileNames = glob(dirname(__FILE__)."/../build/*.state.xml");

foreach($fileNames as $fileName) {
    echo $fileName,PHP_EOL;
    $parser            = new SpecificationParser(new SpecificationFilename($fileName));
    $className         = $parser->getClassName();
    $namespaceName     = "namespace " . $parser->getNamespaceName();
    $abstractClassName = $parser->getAbstractClassName();
    $interfaceName     = $parser->getInterfaceClassName();
    $operations        = $parser->getOperations();
    $queries           = $parser->getQueries();
    $states            = $parser->getStates();

    $generator = new InterfaceGenerator;
    $generator->generate($operations, $interfaceName, $namespaceName);

    $generator = new AbstractStateClassGenerator;
    $generator->generate($operations, $abstractClassName, $interfaceName, $namespaceName);

    $generator = new ClassGenerator;
    $generator->generate($operations, $states, $className, $interfaceName, $namespaceName);

    $codeGenerator = new StateClassGenerator;
    $testGenerator = new TestGenerator;

    foreach ($states as $state => $data) {
        $codeGenerator->generate($data, $state, $abstractClassName, $namespaceName);
        $testGenerator->generate($data, $operations, $queries, $states, $state, $className, $abstractClassName, $namespaceName);
    }
}