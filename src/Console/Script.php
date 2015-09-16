<?php
/**
 * Project: php-phantomjs
 * File: Script.php 2015-09-15 17:56
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

use Core12\Phantomjs\ClientInterface;


/**
 * Class Script
 * @package Core12\Phantomjs\Console
 */
abstract class Script implements ScriptInterface
{
    /**
     * @param ClientInterface $client
     * @param array|null $args
     * @return array
     */
    public function execute(ClientInterface $client, array $args = null)
    {
        $executable = $this->createExecutable();
        $command    = $this->createCommand($client, $executable, $args);

        $process = new Process($command);
        $process->close();

        $this->removeExecutable($executable);
        return $this->createOutput($process->getOutput(), $process->getErrors());
    }

    /**
     * @return string
     */
    abstract public function compile();

    /**
     * @param $output
     * @param $errors
     * @return array
     */
    protected function createOutput($output, $errors)
    {
        return [
            'output' => $output,
            'errors' => $errors
        ];
    }

    /**
     * @return string
     */
    protected function createExecutable()
    {
        $path = tempnam(sys_get_temp_dir(), 'phj_');
        file_put_contents($path, $this->compile());

        return $path;
    }

    /**
     * @param ClientInterface $client
     * @param $executable
     * @param array|null $args
     * @return string
     */
    protected function createCommand(ClientInterface $client, $executable, array $args = null)
    {
        $command = $client->getBrowser()->getCommand() . ' ' . $executable;

        if ($args) {
            $command.= ' ' . implode(' ', $args);
        }

        return trim($command);
    }

    /**
     * @param $path
     */
    protected function removeExecutable($path)
    {
        if (is_file($path)) {
            unlink($path);
        }
    }
}