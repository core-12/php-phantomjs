<?php
/**
 * Project: php-phantomjs
 * File: ScriptInterface.php 2015-09-15 17:41
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
 * Interface ScriptInterface
 * @package Core12\Phantomjs\Console
 */
interface ScriptInterface
{
    /**
     * @param ClientInterface $client
     * @param array|null $args
     * @return array
     */
    public function execute(ClientInterface $client, array $args = null);
}