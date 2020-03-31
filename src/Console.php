<?php

namespace crystlbrd\PHPCLIFrame;

use crystlbrd\Values\ArrVal;

class Console
{
    protected $BackgroundColors = [
        'black' => 40,
        'red' => 41,
        'green' => 42,
        'yellow' => 43,
        'blue' => 44,
        'purple' => 45,
        'cyan' => 46,
        'light_grey' => 47
    ];

    protected $ConsoleWidth;

    protected $Modes = [
        'default' => 0,
        'bold' => 1,
        'half_bright' => 2,
        'italic' => 3,
        'underscore' => 4,
        'blink' => 5,
        'reverse' => 7,
        'hidden' => 8
    ];

    protected $TextColors = [
        'black' => '0;30',
        'dark_grey' => '1;30',
        'red' => '0;31',
        'light_red' => '1;31',
        'green' => '0;32',
        'light_green' => '1;32',
        'yellow' => '1;33',
        'brown' => '0;33',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'magenta' => '0;35',
        'light_magenta' => '1;35',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'light_grey' => '0;37',
        'white' => '0;37'
    ];

    public function __construct(int $ConsoleWidth)
    {
        $this->ConsoleWidth = $ConsoleWidth;
    }

    public function alignText(string $text, string $alignment): string
    {
        switch ($alignment) {
            case 'left':
            default:
                $ws = str_repeat(' ', $this->ConsoleWidth - strlen($text));
                return $text . $ws;
                break;
            case 'right':
                $ws = str_repeat(' ', $this->ConsoleWidth - strlen($text));
                return $ws . $text;
                break;
            case 'center':
                $ws = str_repeat(' ', ($this->ConsoleWidth - strlen($text)) / 2);
                return $ws . $text . $ws;
                break;
        }
    }

    public function printException(Exception $e): void
    {
        # TODO
        $this->println($e->getMessage(), [
            'color' => 'white',
            'background' => 'red',
            'mode' => 'bold'
        ]);

        exit;
    }

    public function println(string $msg, array $options = []): void
    {
        // defaults
        $o = ArrVal::merge([
            'align' => 'left',                  // Text alignment
            'color' => '',                      // Text color
            'mode' => '',                       // Text mode
            'background' => '',                 // Background color
            'channel' => STDOUT,                // Output channel
        ], $options);

        if ($o['color']) $this->setTextColor($o['color']);
        if ($o['mode']) $this->setTextMode($o['mode']);
        if ($o['background']) $this->setBackgroundColor($o['background']);

        $text = $this->alignText($msg, $o['align']);

        fwrite($o['channel'], $text);
        $this->printLineBreak($o['channel']);
    }

    public function printLineBreak($channel = STDOUT): void
    {
        fwrite($channel, PHP_EOL);
    }

    public function reset(): void
    {
        $this->writeSequence('c');
    }

    public function resetColors(): void
    {
        $this->writeSequence('[0m');
    }

    public function setBackgroundColor(string $color): void
    {
        if (isset($this->BackgroundColors[$color])) {
            $this->writeSequence('[' . $this->BackgroundColors[$color] . 'm');
        }
    }

    public function setTextColor(string $color): void
    {
        if (isset($this->TextColors[$color])) {
            $this->writeSequence('[' . $this->TextColors[$color] . 'm');
        }
    }

    public function setTextMode(string $mode): void
    {
        # TODO
    }

    private function writeSequence(string $sequence): void
    {
        fwrite(STDOUT, "\e" . $sequence);
    }
}