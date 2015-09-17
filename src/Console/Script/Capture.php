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

use Core12\Phantomjs\ClientInterface;
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
    private $html;

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
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

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
        $this->rectWidth = (int)$width;
        $this->rectHeight = (int)$height;
        $this->rectTop = (int)$top;
        $this->rectLeft = (int)$left;

        return $this;
    }

    /**
     * @param ClientInterface $client
     * @param array|null $args
     * @return array
     */
    public function execute(ClientInterface $client, array $args = null)
    {
        $fileHtml = null;

        if ($html = $this->getHtml()) {
            $fileHtml = sys_get_temp_dir() . '/' . uniqid('phj_') . '.html';
            file_put_contents($fileHtml, $html);

            $this->setUrl('file://' . $fileHtml);
        }

        try {
            $output = parent::execute($client, $args);
            return $output;
        } finally {
            if ($fileHtml && is_file($fileHtml)) {
                unlink($fileHtml);
            }
        }
    }

    /**
     * @return string
     */
    public function compile()
    {
        $script = $this->compileVariablesGlobal();
        $script.= $this->compileVariablesOptions();
        $script.= $this->compileFunctionRender();

        return $script;
    }

    /**
     * @return string
     */
    protected function compileVariablesGlobal()
    {
        $script = 'var system  = require(\'system\'); ' . PHP_EOL;
        $script.= 'var webpage = require(\'webpage\'); ' . PHP_EOL;
        $script.= 'var page    = webpage.create();' . PHP_EOL;

        return $script;
    }

    /**
     * @return null|string
     */
    protected function compileVariablesOptions()
    {
        if ($this->getRectWidth() && $this->getRectHeight()) {
            $clipRect = [
                'top' => $this->getRectTop(),
                'left' => $this->getRectLeft(),
                'width' => $this->getRectWidth(),
                'height' => $this->getRectHeight()
            ];

            return 'page.clipRect = ' . json_encode($clipRect) . ';' . PHP_EOL;
        }

        return null;
    }

    /**
     * @return string
     */
    protected function compileFunctionRender()
    {
        $script = 'page.open(\'' . $this->getUrl() . '\', function(status) {' . PHP_EOL;
        $script.= '   if (status) {' . PHP_EOL;
        $script.= '      page.render(\'' . $this->getOutputFile() . '\');' . PHP_EOL;
        $script.= '      system.stdout.write(JSON.stringify({"status": true, "file": "' . $this->getOutputFile() . '"}));' . PHP_EOL;
        $script.= '   }' . PHP_EOL;
        $script.= '   phantom.exit();' . PHP_EOL;
        $script.= '});' . PHP_EOL;

        return $script;
    }
}