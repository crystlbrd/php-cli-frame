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

        $this->printLine('I am right!', ['align' => 'right']);
        $this->printLine('And I am centered!', ['align' => 'center']);

        $this->printLine('Printing a line, which is way longer than the actuall console, so it is basically breaking the design completely!');
        $this->printLine('Printing a line, which is way longer than the actuall console, so it is basically breaking the design completely!', [
            'align' => 'right'
        ]);
        $this->printLine('Printing a line, which is way longer than the actuall console, so it is basically breaking the design completely!', [
            'align' => 'center'
        ]);

        $this->log('Printing a line, which is way longer than the actuall console, so it is basically breaking the design completely, but now it is printed in multiple lines!');

        $this->logException(new Exception('This is an exception!', 0, new Exception('And I\'m a traced exception!')));
    }
}


new TestScript();