<?php

namespace crystlbrd\PHPCLIFrame;

class PHPCLIScipt
{
    /// ENVIRONMENT

    /**
     * @var Console the current CLI environment
     */
    protected $Environment;

    /**
     * @var int Time the script started
     */
    protected $TimerStart = 0;

    /**
     * @var int Time the script stopped
     */
    protected $TimerEnd = 0;

    /**
     * @var array Logs
     */
    protected $Logs = [];


    /// STYLE

    /**
     * @var int available characters in a line
     */
    protected $ConsoleWidth = 50;


    /// SCRIPT PROPERTIES

    /**
     * @var string the script name
     */
    protected $ScriptName;

    /**
     * @var string the script version
     */
    protected $ScriptVersion;

    /**
     * @var array the script authors
     */
    protected $ScriptAuthors = [];

    /**
     * @var string a description of the script
     */
    protected $ScriptDescription;


    /// OPTIONS

    /**
     * @var string|bool path, relative to script file, where to save log files. false to disable logging
     */
    protected $LogPath = 'logs/';


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

        // start internal timer
        $this->startTimer();

        // determine the current CLI environment
        $this->Environment = $this->getEnvironment();

        // print header
        $this->printHeader();
    }

    public function __destruct()
    {
        $this->printEndline();

        if ($this->LogPath) {
            $this->exportLogsToFile($this->LogPath . date('Y-m-d-H-i') . '.log');
        }
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

    protected function log(string $msg, array $options = []): void
    {
        $this->Environment->printmln($msg, $options);
        $this->Logs[] = '(' . gmdate('D M d H:i:s Y') . ') : ' . $msg;
    }

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
        if ($this->ScriptVersion) {
            $this->printLine($this->ScriptVersion, ['align' => 'right', 'color' => 'light_grey', 'background' => 'cyan']);
        }

        $this->printEmptyLine();
    }

    protected function printEndline()
    {
        $this->stopTimer();
        $this->printEmptyLine();
        $this->printLine('script ended after ' . round($this->getFinalTime(), 3) . 's', [
            'color' => 'white',
            'background' => 'cyan'
        ]);
    }


    /// Default Log Methods

    protected function logDebug(string $msg): void
    {
        $this->log($msg, [
            'color' => 'light_grey'
        ]);

        $this->Environment->resetColors();
    }

    protected function logInfo(string $msg): void
    {
        $this->log($msg);
    }

    protected function logWarning(string $msg): void
    {
        $this->log($msg, [
            'color' => 'black',
            'background' => 'yellow'
        ]);

        $this->Environment->resetColors();
    }

    protected function logError(string $msg): void
    {
        $this->log($msg, [
            'color' => 'white',
            'background' => 'red'
        ]);

        $this->Environment->resetColors();
    }

    protected function logException(\Exception $e): void
    {
        $this->Environment->printException($e);
    }

    protected function startTimer(): void
    {
        $this->TimerStart = microtime(true);
    }

    protected function stopTimer(): void
    {
        $this->TimerEnd = microtime(true);
    }

    protected function getCurrentTime(): float
    {
        return microtime(true) - $this->TimerStart;
    }

    protected function getFinalTime(): float
    {
        return $this->TimerEnd - $this->TimerStart;
    }

    protected function exportLogsToFile(string $path): void
    {
        // Create dir
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // open file
        $f = fopen($path, 'w');

        // write logs
        if ($f) {
            foreach ($this->Logs as $log) {
                fwrite($f, $log . PHP_EOL);
            }
        } else {
            throw new Exception('Failed to open log (' . $path . ') file!');
        }
    }
}