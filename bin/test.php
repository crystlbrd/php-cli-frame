<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';


class TestScript extends crystlbrd\PHPCLIFrame\PHPCLIScipt
{
    protected $ScriptName = 'TestScript';
    protected $SciptVersion = 'v1.0.0';
}


new TestScript();