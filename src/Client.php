<?php
/**
 * Project: php-phantomjs
 * File: Client.php 2015-09-15 17:38
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


/**
 * Class Client
 * @package Core12\Phantomjs
 */
class Client implements ClientInterface
{
    private $browser;

    /**
     * @param Browser $browser
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        $browser = new Browser();
        return new static($browser);
    }

    /**`
     * @return Browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param ScriptInterface $script
     * @param array|null $args
     * @return array
     */
    public function execute(ScriptInterface $script, array $args = null)
    {
        return $script->execute($this, $args);
    }
}
