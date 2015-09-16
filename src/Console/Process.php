<?php
/**
 * Project: php-phantomjs
 * File: Process.php 2015-09-16 13:02
 * ----------------------------------------------
 *
 * @author      Stanislav Kiryukhin <korsar.zn@gmail.com>
 * @copyright   Copyright (c) 2015, Core12 Team
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace Core12\Phantomjs\Console;


/**
 * Class Process
 * @package Core12\Phantomjs\Console
 */
class Process
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var resource
     */
    protected $stdin;

    /**
     * @var resource
     */
    protected $stdout;

    /**
     * @var resource
     */
    protected $stderr;

    /**
     * @var string
     */
    protected $output;

    /**
     * @var string
     */
    protected $errors;

    /**
     * @param $command
     * @param bool|true $autoRun
     */
    public function __construct($command, $autoRun = true)
    {
        $this->command = $command;

        if ($autoRun) {
            $this->run();
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return resource
     */
    public function getStdIn()
    {
        return $this->stdin;
    }

    /**
     * @return resource
     */
    public function getStdOut()
    {
        return $this->stdout;
    }

    /**
     * @return resource
     */
    public function getStdErr()
    {
        return $this->stderr;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return resource
     * @throws FailedException
     */
    public function run()
    {
        if ($this->resource) {
            return $this->resource;
        }

        $this->resource = proc_open(escapeshellcmd($this->command), [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w']
        ], $pipes);

        if (!is_resource($this->resource)) {
            throw new FailedException('proc_open() did not return a resource');
        }

        $this->stdin  = $pipes[0];
        $this->stdout = $pipes[1];
        $this->stderr = $pipes[2];

        return $this->resource;
    }

    /**
     * @param $str
     * @return int
     */
    public function write($str)
    {
        return fwrite($this->getStdIn(), $str);
    }

    /**
     * @return int
     */
    public function close()
    {
        if (!is_resource($this->resource)) {
            return false;
        }

        $this->output = stream_get_contents($this->getStdOut());
        $this->errors = stream_get_contents($this->getStdErr());

        fclose($this->getStdIn());
        fclose($this->getStdOut());
        fclose($this->getStdErr());

        return proc_close($this->resource);
    }
}