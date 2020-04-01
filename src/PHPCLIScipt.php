<?php

namespace crystlbrd\PHPCLIFrame;

class PHPCLIScipt
{
    /// ENVIRONMENT

    /**
     * @var Console the current CLI environment
     */
    protected $Environment;


    /// STYLE

    /**
     * @var int available characters in a line
     */
    protected $ConsoleWidth = 50;

    /**
     * @var string background color
     */
    protected $BackgroundColor = 'default';

    /**
     * @var string text color
     */
    protected $FontColor = 'default';


    /// SCRIPT PROPERTIES

    /**
     * @var string the script name
     */
    protected $ScriptName;

    /**
     * @var string the script version
     */
    protected $SciptVersion;

    /**
     * @var array the script authors
     */
    protected $ScriptAuthors = [];

    /**
     * @var string a description of the script
     */
    protected $ScriptDescription;


    /// METHODS

    /**
     * PHPCLIScipt constructor.
     * @throws Exception
     */
    public function __construct()
    {
        // check, if script is running in CLI environment
        if (!$this->validateEnvironment()) {
            throw new Exception('This script is intended to run in the PHP CLI!');
        }

        // determine the current CLI environment
        $this->Environment = $this->getEnvironment();

        // print header
        $this->printHeader();
    }


    /// ENVIRONMENT HANDLING

    /**
     * Checks, if the current script is running in CLI
     * @return bool
     */
    public function validateEnvironment(): bool
    {
        if (defined('STDIN')) {
            return true;
        } else if (php_sapi_name() === 'cli') {
            return true;
        } else if (array_key_exists('SHELL', $_ENV)) {
            return true;
        } else if (empty($_SERVER['REMOTE_ADDR']) && !isset($_SERVER['HTTP_USER_AGENT']) && count($_SERVER['argv']) > 0) {
            return true;
        } else if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determines the current CLI environment
     * @return Console
     */
    public function getEnvironment(): Console
    {
        return new Console($this->ConsoleWidth);
    }


    /// PRINTING

    /**
     * Prints a text to the console
     * @param string $msg
     * @param array $options
     */
    protected function printLine(string $msg, array $options = []): void
    {
        $this->Environment->println($msg, $options);
    }

    protected function printEmptyLine()
    {
        $this->Environment->printLineBreak();
    }

    protected function printHeader(): void
    {
        // opening line
        $this->printLine(date('d-m-Y, H:i'), ['color' => 'light_grey', 'background' => 'cyan']);

        // script name
        if ($this->ScriptName != null) {
            $this->printLine($this->ScriptName, ['align' => 'center', 'color' => 'white', 'background' => 'cyan']);
        }

        // script version
        if ($this->SciptVersion) {
            $this->printLine($this->SciptVersion, ['align' => 'right', 'color' => 'light_grey', 'background' => 'cyan']);
        }

        $this->printEmptyLine();
    }


    /// Default Log Methods

    protected function logDebug(string $msg): void
    {
        $this->printLine($msg, [
            'color' => 'light_grey'
        ]);

        $this->Environment->resetColors();
    }

    protected function logInfo(string $msg): void
    {
        $this->printLine($msg);
    }

    protected function logWarning(string $msg): void
    {
        $this->printLine($msg, [
            'color' => 'black',
            'background' => 'yellow'
        ]);

        $this->Environment->resetColors();
    }

    protected function logError(string $msg): void
    {
        $this->printLine($msg, [
            'color' => 'white',
            'background' => 'red'
        ]);

        $this->Environment->resetColors();
    }

    protected function logException(\Exception $e): void
    {
        $this->Environment->printException($e);
    }
}