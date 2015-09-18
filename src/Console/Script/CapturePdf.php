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

        $script = parent::compileVariablesOptions();
        $script.= 'page.paperSize = ' . json_encode($paperSize) . ';' . PHP_EOL;
        return $script;
    }
}