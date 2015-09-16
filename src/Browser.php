<?php
/**
 * Project: php-phantomjs
 * File: Browser.php 2015-09-15 15:32
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

/**
 * Class Browser
 * @package Core12\Phantomjs
 */
class Browser
{
    const SSL_PROTOCOL_TYPE_SSLV2 = 'sslv2';
    const SSL_PROTOCOL_TYPE_SSLV3 = 'sslv3';
    const SSL_PROTOCOL_TYPE_TLSV1 = 'tlsv1';
    const SSL_PROTOCOL_TYPE_ANY   = 'any';

    const PROXY_TYPE_HTTP   = 'http';
    const PROXY_TYPE_socks5 = 'socks5';
    const PROXY_TYPE_NONE   = 'none';

    const OPTION_PROXY_AUTH             = '--proxy-auth';
    const OPTION_PROXY_TYPE             = '--proxy-type';
    const OPTION_PROXY_ADDRESS          = '--proxy-address';
    const OPTION_SSL_PROTOCOL           = '--ssl-protocol';
    const OPTION_SSL_CERTIFICATES_PATH  = '--ssl-certificates-path';

    /**
     * @var string
     */
    private $path = '/bin/phantomjs';

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string
     */
    private $version;


    public function __construct()
    {
        $this->autoDetectPath();
    }


    /**
     * @param string $path
     * @return Browser
     */
    public function setPath($path)
    {
        $this->path    = $path;
        $this->version = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param array $options
     * @return Browser
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $option
     * @param null $value
     * @return Browser
     */
    public function setOption($option, $value = null)
    {
        if ($value !== null) {
            $this->options[$option] = $value;
        } else {
            $this->options[] = $option;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->getPath() . ' ' . $this->combineOptions();
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        if (!$this->version) {
            $this->version = exec($this->getPath() . ' -v', $output);
        }

        return $this->version;
    }

    /**
     * @return string
     */
    protected function combineOptions()
    {
        $options = [];

        foreach ($this->getOptions() as $option => $value) {
            if (is_int($option)) {
                $options[] = $value;
            } else {
                $options[] = $option . '=' . $value;
            }
        }

        return implode(' ', $options);
    }

    /**
     *
     */
    protected function autoDetectPath()
    {
        if ($bin = exec('which phantomjs', $output)) {
            $this->setPath($bin);
        }
    }
}
