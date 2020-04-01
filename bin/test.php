<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';


class TestScript extends crystlbrd\PHPCLIFrame\PHPCLIScipt
{
    protected $ScriptName = 'TestScript';
    protected $SciptVersion = 'v1.0.0';

    public function __construct()
    {
        // init frame
        parent::__construct();

        $this->logDebug('This is a non important debug log');
        $this->logInfo('This is a simple info log');
        $this->logWarning('This is a warning and should be noticed!');
        $this->logError('This is an error and is important!');
        $this->logException(new Exception('This is an exception!'), 0, new Exception('And I\'m a traced exception!'));
    }
}


new TestScript();