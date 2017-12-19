<?php

namespace Inhere\Console\Examples\Controllers;

use Inhere\Console\Components\AnsiCode;
use Inhere\Console\Components\Download;
use Inhere\Console\Controller;
use Inhere\Console\IO\Input;
use Inhere\Console\Utils\Helper;
use Inhere\Console\Utils\Interact;
use Inhere\Console\Utils\Show;

/**
 * default command controller. there are some command usage examples(1)
 * Class HomeController
 * @package Inhere\Console\Examples\Controllers
 */
class HomeController extends Controller
{
    protected static $description = 'default command controller. there are some command usage examples(2)';

    /**
     * @return array
     */
    protected static function commandMap()
    {
        return [
            'i' => 'index',
            'prg' => 'progress',
        ];
    }

    /**
     * this is a command's description message
     * the second line text
     * @usage usage message
     * @arguments
     *  arg1  argument description 1
     *  arg2  argument description 2
     * @options
     *  -s, --long  option description 1
     *  --opt      option description 2
     * @example example text one
     *  the second line example
     */
    public function indexCommand()
    {
        $this->write('hello, welcome!! this is ' . __METHOD__);
    }

    /**
     * a example for use color text output on command
     * @usage {fullCommand}
     */
    public function colorCommand()
    {
        if (!$this->output->supportColor()) {
            $this->write('Current terminal is not support output color text.');

            return 0;
        }

        $this->write('color text output:');
        $styles = $this->output->getStyle()->getStyleNames();

        foreach ($styles as $style) {
            $this->output->write("<$style>$style style text</$style>");
        }

        return 0;
    }

    /**
     * output block message text
     * @return int
     */
    public function blockMsgCommand()
    {
        $this->write('block message:');

        foreach (Show::getBlockMethods() as $type) {
            $this->output->$type("$type style message text");
        }

        return 0;
    }

    /**
     * a counter example show. It is like progress txt, but no max value.
     * @example
     *  {script} {command}
     * @return int
     */
    public function counterCommand()
    {
        $total = 120;
        $ctr = Show::counterTxt('handling ...', 'handled.');
        $this->write('Counter:');

        while ($total - 1) {
            $ctr->send(1);
            usleep(30000);
            $total--;
        }

        // end of the counter.
        $ctr->send(-1);

        return 0;
    }

    /**
     * a progress bar example show
     * @options
     *  --type      the progress type, allow: bar,txt. <cyan>txt</cyan>
     *  --done-char the done show char. <info>=</info>
     *  --wait-char the waiting show char. <info>-</info>
     *  --sign-char the sign char show. <info>></info>
     * @example
     *  {script} {command}
     *  {script} {command} --done-char '#' --wait-char ' '
     * @param Input $input
     * @return int
     */
    public function progressCommand($input)
    {
        $i = 0;
        $total = 120;
        if ($input->getOpt('type') === 'bar') {
            $bar = $this->output->progressBar($total, [
                'msg' => 'Msg Text',
                'doneMsg' => 'Done Msg Text',
                'doneChar' => $input->getOpt('done-char', '='), // ▓
                'waitChar' => $input->getOpt('wait-char', '-'), // ░
                'signChar' => $input->getOpt('sign-char', '>'),
            ]);
        } else {
            $bar = $this->output->progressTxt($total, 'Doing gggg ...', 'Done');
        }

        $this->write('Progress:');

        while ($i <= $total) {
            $bar->send(1);
            usleep(50000);
            $i++;
        }

        return 0;
    }

    /**
     * output format message: title
     */
    public function titleCommand()
    {
        $this->output->title('title show');

        return 0;
    }

    /**
     * output format message: section
     */
    public function sectionCommand()
    {
        $body = 'If screen size could not be detected, or the indentation is greater than the screen size, the text will not be wrapped.' .
            'Word wrap text with indentation to fit the screen size,' .
            'Word wrap text with indentation to fit the screen size,' .
            'Word wrap text with indentation to fit the screen size,' .
            'Word wrap text with indentation to fit the screen size,';

        $this->output->section('section show', $body, [
            'pos' => 'l'
        ]);

        return 0;
    }

    /**
     * output format message: panel
     */
    public function panelCommand()
    {
        $data = [
            'application version' => '1.2.0',
            'system version' => '5.2.3',
            'see help' => 'please use php bin/app -h',
            'a only value message text',
        ];

        Show::panel($data, 'panel show', [
            'borderChar' => '#'
        ]);
    }

    /**
     * output format message: helpPanel
     */
    public function helpPanelCommand()
    {
        Show::helpPanel([
            Show::HELP_DES => 'a help panel description text. (help panel show)',
            Show::HELP_USAGE => 'a usage text',
            Show::HELP_ARGUMENTS => [
                'arg1' => 'arg1 description',
                'arg2' => 'arg2 description',
            ],
            Show::HELP_OPTIONS => [
                '--opt1' => 'a long option',
                '-s' => 'a short option',
                '-d' => 'Run the server on daemon.(default: <comment>false</comment>)',
                '-h, --help' => 'Display this help message'
            ],
        ], false);
    }

    /**
     * output format message: aList
     */
    public function aListCommand()
    {
        $list = [
            'The is a list line 0',
            'The is a list line 1',
            'The is a list line 2',
            'The is a list line 3',
        ];

        Show::aList($list, 'a List show(No key)');

        $commands = [
            'version' => 'Show application version information',
            'help' => 'Show application help information',
            'list' => 'List all group and independent commands',
            'a only value message text'
        ];

        Show::aList($commands, 'a List show(Has key)');
    }

    /**
     * output format message: table
     */
    public function tableCommand()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'john',
                'status' => 2,
                'email' => 'john@email.com',
            ],
            [
                'id' => 2,
                'name' => 'tom',
                'status' => 0,
                'email' => 'tom@email.com',
            ],
            [
                'id' => 3,
                'name' => 'jack',
                'status' => 1,
                'email' => 'jack-test@email.com',
            ],
        ];
        Show::table($data, 'table show');

        Show::table($data, 'No border table show', [
            'showBorder' => 0
        ]);

        Show::table($data, 'change style table show', [
            'bodyStyle' => 'info'
        ]);

        $data1 = [
            [
                'Walter White',
                'Father',
                'Teacher',
            ],
            [
                'Skyler White',
                'Mother',
                'Accountant',
            ],
            [
                'Walter White Jr.',
                'Son',
                'Student',
            ],
        ];

        Show::table($data1, 'no head table show');
    }

    /**
     * output format message: padding
     */
    public function paddingCommand()
    {
        $data = [
            'Eggs' => '$1.99',
            'Oatmeal' => '$4.99',
            'Bacon' => '$2.99',
        ];

        Show::padding($data, 'padding data show');
    }

    /**
     * output format message: dump
     */
    public function jsonCommand()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'john',
                'status' => 2,
                'email' => 'john@email.com',
            ],
            [
                'id' => 2,
                'name' => 'tom',
                'status' => 0,
                'email' => 'tom@email.com',
            ],
            [
                'id' => 3,
                'name' => 'jack',
                'status' => 1,
                'email' => 'jack-test@email.com',
            ],
        ];

        $this->output->write('use dump:');
        $this->output->dump($data);

        $this->output->write('use print:');
        $this->output->prints($data);

        $this->output->write('use json:');
        $this->output->json($data);
    }

    /**
     * a example for use arguments on command
     * @usage home:useArg [arg1=val1 arg2=arg2] [options]
     * @example
     *  home:useArg status=2 name=john arg0 -s=test --page=23 -d -rf --debug --test=false
     *  home:useArg status=2 name=john name=tom name=jack arg0 -s=test --page=23 --id=23 --id=154 --id=456  -d -rf --debug --test=false
     */
    public function useArgCommand()
    {
        $this->write('input arguments:');
        echo Helper::dumpVars($this->input->getArgs());

        $this->write('input options:');
        echo Helper::dumpVars($this->input->getOpts());

        // $this->write('the Input object:');
        // var_dump($this->input);
    }

    /**
     * command `defArgCommand` config
     * @throws \LogicException
     */
    protected function defArgConfigure()
    {
        $this->createDefinition()
            ->setDescription('the command arg/opt config use defined configure, it like symfony console: argument define by position')
            ->addArgument('name', Input::ARG_REQUIRED, 'description for the argument [name]')
            ->addOption('yes', 'y', Input::OPT_BOOLEAN, 'description for the option [yes]')
            ->addOption('opt1', null, Input::OPT_REQUIRED, 'description for the option [opt1]');
    }

    /**
     * the command arg/opt config use defined configure, it like symfony console: argument define by position
     */
    public function defArgCommand()
    {
        $this->output->dump($this->input->getArgs(), $this->input->getOpts(), $this->input->getBoolOpt('y'));
    }

    /**
     * This is a demo for use <magenta>Interact::confirm</magenta> method
     */
    public function confirmCommand()
    {
        // can also: $this->confirm();
        $a = Interact::confirm('continue');

        $this->write('Your answer is: ' . ($a ? 'yes' : 'no'));
    }

    /**
     * This is a demo for use <magenta>Interact::select()</magenta> method
     */
    public function selectCommand()
    {
        $opts = ['john', 'simon', 'rose'];
        // can also: $this->select();
        $a = Interact::select('you name is', $opts);

        $this->write('Your answer is: ' . $opts[$a]);
    }

    /**
     * This is a demo for use <magenta>Interact::multiSelect()</magenta> method
     */
    public function msCommand()
    {
        $opts = ['john', 'simon', 'rose', 'tom'];

        // can also: $a = Interact::multiSelect('Your friends are', $opts);
        $a = $this->multiSelect('Your friends are', $opts);

        $this->write('Your answer is: ' . json_encode($a));
    }

    /**
     * This is a demo for use <magenta>Interact::ask()</magenta> method
     */
    public function askCommand()
    {
        $a = Interact::ask('you name is: ', null, function ($val, &$err) {
            if (!preg_match('/^\w{2,}$/', $val)) {
                $err = 'Your input must match /^\w{2,}$/';

                return false;
            }

            return true;
        });

        $this->write('Your answer is: ' . $a);
    }

    /**
     * This is a demo for use <magenta>Interact::limitedAsk()</magenta> method
     * @options
     *  --nv   Not use validator.
     *  --limit  limit times.(default: 3)
     */
    public function limitedAskCommand()
    {
        $times = (int)$this->input->getOpt('limit', 3);

        if ($this->input->getBoolOpt('nv')) {
            $a = Interact::limitedAsk('you name is: ', null, null, $times);
        } else {
            $a = Interact::limitedAsk('you name is: ', null, function ($val) {
                if (!preg_match('/^\w{2,}$/', $val)) {
                    Show::error('Your input must match /^\w{2,}$/');

                    return false;
                }

                return true;
            }, $times);
        }

        $this->write('Your answer is: ' . $a);
    }

    /**
     * This is a demo for input password on command line. use: <magenta>Interact::askPassword()</magenta>
     * @usage {fullCommand}
     */
    public function pwdCommand()
    {
        $pwd = $this->askPassword();

        $this->write('Your input is: ' . $pwd);
    }

    /**
     * output current env info
     */
    public function envCommand()
    {
        $info = [
            'phpVersion' => PHP_VERSION,
            'env' => 'test',
            'debug' => true,
        ];

        Interact::panel($info);

        echo Helper::printVars($_SERVER);
    }

    /**
     * This is a demo for download a file to local
     * @usage {command} url=url saveTo=[saveAs] type=[bar|text]
     * @example {command} url=https://github.com/inhere/php-console/archive/master.zip type=bar
     */
    public function downCommand()
    {
        $url = $this->input->getArg('url');

        if (!$url) {
            $this->output->liteError('Please input you want to downloaded file url, use: url=[url]', 1);
        }

        $saveAs = $this->input->getArg('saveAs');
        $type = $this->input->getArg('type', 'text');

        if (!$saveAs) {
            $saveAs = __DIR__ . '/' . basename($url);
        }

        $goon = Interact::confirm("Now, will download $url \nto dir $saveAs, go on");

        if (!$goon) {
            Show::notice('Quit download, Bye!');

            return 0;
        }

        $d = Download::down($url, $saveAs, $type);

        // echo Helper::dumpVars($d);

        return 0;
    }

    /**
     * This is a demo for show cursor move on the screen
     */
    public function cursorCommand()
    {
        $this->write('hello, this in ' . __METHOD__);

        // $this->output->panel($_SERVER, 'Server information', '');

        $this->write('this is a message text.', false);

        sleep(1);
        AnsiCode::make()->cursor(AnsiCode::CURSOR_BACKWARD, 6);

        sleep(1);
        AnsiCode::make()->cursor(AnsiCode::CURSOR_FORWARD, 3);

        sleep(1);
        AnsiCode::make()->cursor(AnsiCode::CURSOR_BACKWARD, 2);

        sleep(2);

        AnsiCode::make()->screen(AnsiCode::CLEAR_LINE, 3);

        $this->write('after 2s scroll down 3 row.');

        sleep(2);

        AnsiCode::make()->screen(AnsiCode::SCROLL_DOWN, 3);

        $this->write('after 3s clear screen.');

        sleep(3);

        AnsiCode::make()->screen(AnsiCode::CLEAR);
    }
}