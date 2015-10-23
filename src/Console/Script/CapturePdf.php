<?php
/**
 * Project: php-phantomjs
 * File: CapturePdf.php 2015-09-16 14:36
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


/**
 * Class CapturePdf
 * @package Core12\Phantomjs\Console\Script
 */
class CapturePdf extends Capture
{
    const FORMAT_A3       = 'A3';
    const FORMAT_A4       = 'A4';
    const FORMAT_A5       = 'A5';
    const FORMAT_LEGAL    = 'Legal';
    const FORMAT_LETTER   = 'Letter';
    const FORMAT_TABLOID  = 'Tabloid';

    const ORIENTATION_PORTRAIT  = 'portrait';
    const ORIENTATION_LANDSCAPE = 'landscape';

    private $orientation = self::ORIENTATION_PORTRAIT;
    private $width;
    private $height;
    private $format =  self::FORMAT_A4;
    private $margin;

    /**
     * @var array
     */
    private $header;

    /**
     * @var array
     */
    private $footer;


    /**
     * @return string
     */
    public function getOutputFile()
    {
        if (!$outputFile = parent::getOutputFile()) {
            $outputFile = sys_get_temp_dir() . '/' . uniqid('phj_') . '.pdf';
            $this->setOutputFile($outputFile);
        }

        return $outputFile;
    }

    /**
     * @param $width
     * @param $height
     * @return $this
     */
    public function setSize($width, $height)
    {
        $this->setWidth($width);
        $this->setHeight($height);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * @param $margin
     * @return $this
     */
    public function setMargin($margin)
    {
        $this->margin = $margin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * @param $orientation
     * @return $this
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param $content
     * @param string $height
     * @return $this
     */
    public function setHeader($content, $height = '1cm')
    {
        $this->header = [
            'height' => $height,
            'contents' => 'phantom.callback(function(pageNum, numPages) { return ' . str_replace('"', '\'', $content) . '; })'
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param $content
     * @param string $height
     * @return $this
     */
    public function setFooter($content, $height = '1cm')
    {
        $this->footer = [
            'height' => $height,
            'contents' => 'phantom.callback(function(pageNum, numPages) { return ' . str_replace('"', '\'', $content) . '; })'
        ];
        return $this;
    }


    /**
     * @return null|string
     */
    protected function compileVariablesOptions()
    {
        $paperSize = [
            'orientation' => $this->getOrientation(),
            'format' => $this->getFormat()
        ];

        if ($margin = $this->getMargin()) {
            $paperSize['margin'] = $margin;
        }

        if ($this->getWidth() && $this->getHeight()) {
            $paperSize['width']  = $this->getWidth();
            $paperSize['height'] = $this->getHeight();
            unset($paperSize['format']);
        }

        $indents = '    ';
        $script = parent::compileVariablesOptions();
        $script.= 'page.paperSize = {' . PHP_EOL;

        foreach ($paperSize as $key => $value) {
            $script.= $indents . $key . ': ' . (is_scalar($value) ? '"' . $value . '"' : json_encode($value)) . ',' . PHP_EOL;
        }

        if ($header = $this->getHeader()) {
            $script.= $indents . 'header: {' . PHP_EOL;
            $script.= $indents . $indents . 'height: "' . $header['height'] . '",' . PHP_EOL;
            $script.= $indents . $indents . 'contents: ' . $header['contents'] . PHP_EOL;
            $script.= $indents . '},' . PHP_EOL;
        }

        if ($footer = $this->getFooter()) {
            $script.= $indents . 'footer: {' . PHP_EOL;
            $script.= $indents . $indents . 'height: "' . $footer['height'] . '",' . PHP_EOL;
            $script.= $indents . $indents . 'contents: ' . $footer['contents'] . PHP_EOL;
            $script.= $indents . '},' . PHP_EOL;
        }

        $script.= '};' . PHP_EOL;
        return $script;
    }
}