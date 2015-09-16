<?php
/**
 * Project: php-phantomjs
 * File: Capture.php 2015-09-15 17:56
 * ----------------------------------------------
 *
 * @author      Stanislav Kiryukhin <korsar.zn@gmail.com>
 * @copyright   Copyright (c) 2015, Core12 Team
 *
 * ----------------------------------------------
 * All Rights Reserved.
 * ----------------------------------------------
 */
namespace Core12\Phantomjs\Console\Script;

use Core12\Phantomjs\Console\Script;


/**
 * Class Capture
 * @package Core12\Phantomjs\Console\Script
 */
class Capture extends Script
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $outputFile;

    /**
     * @var int
     */
    private $rectWidth;

    /**
     * @var int
     */
    private $rectHeight;

    /**
     * @var int
     */
    private $rectTop;

    /**
     * @var int
     */
    private $rectLeft;


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getOutputFile()
    {
        return $this->outputFile;
    }

    /**
     * @param $outputFile
     * @return $this
     */
    public function setOutputFile($outputFile)
    {
        $this->outputFile = $outputFile;
        return $this;
    }

    /**
     * @return int
     */
    public function getRectWidth()
    {
        return $this->rectWidth;
    }

    /**
     * @return int
     */
    public function getRectHeight()
    {
        return $this->rectHeight;
    }

    /**
     * @return int
     */
    public function getRectTop()
    {
        return $this->rectTop;
    }

    /**
     * @return int
     */
    public function getRectLeft()
    {
        return $this->rectLeft;
    }

    /**
     * @param $width
     * @param $height
     * @param int $top
     * @param int $left
     * @return $this
     */
    public function setCaptureDimensions($width, $height, $top = 0, $left = 0)
    {
        $this->rectWidth  = (int)$width;
        $this->rectHeight = (int)$height;
        $this->rectTop    = (int)$top;
        $this->rectLeft   = (int)$left;

        return $this;
    }

    /**
     * @return string
     */
    public function compile()
    {
        $script = <<<SCRIPT
            var system = require('system'),
                page   = require('webpage').create();

            page.open('{$this->getUrl()}', function(status) {
                if (status) {
                    page.render('{$this->getOutputFile()}');
                }
                phantom.exit();
            });
SCRIPT;
        return $script;
    }
}