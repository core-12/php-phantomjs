<?php
/**
 * Project: php-phantomjs
 * File: ClientInterface.php 2015-09-15 17:05
 * ----------------------------------------------
 *
 * @author      Stanislav Kiryukhin <korsar.zn@gmail.com>
 * @copyright   Copyright (c) 2015, Core12 Team
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace Core12\Phantomjs;

use Core12\Phantomjs\Console\ScriptInterface;
use Core12\Phantomjs\Console\OutputInterface;

/**
 * Interface ClientInterface
 * @package Core12\Phantomjs
 */
interface ClientInterface
{
    /**
     * @param Browser $browser
     */
    public function __construct(Browser $browser);

    /**
     * @return Browser
     */
    public function getBrowser();

    /**
     * @param ScriptInterface $Script
     * @param array|null $args
     * @return OutputInterface
     */
    public function execute(ScriptInterface $Script, array $args = null);
}
