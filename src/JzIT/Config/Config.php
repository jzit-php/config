<?php

declare(strict_types=1);

namespace JzIT\Config;

use ArrayObject;
use Exception;
use JzIT\Config\Exception\ConfigKeyNotFoundException;
use JzIT\Config\Exception\NotSupportedConfigValueTypeException;

class Config implements ConfigInterface
{
    protected const CONFIG_FILE_PREFIX = 'config-';
    protected const CONFIG_FILE = 'default';
    protected const CONFIG_FILE_SUFFIX = '.php';

    /**
     * @var \ArrayObject|null
     */
    protected $config;

    /**
     * @var string
     */
    protected $appDir;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @param string $appDir
     * @param string $environment
     *
     * @throws Exception
     */
    public function __construct(
        string $appDir,
        string $environment
    ) {
        $this->appDir = $appDir;
        $this->environment = $environment;

        $this->initialize();
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return string
     *
     * @throws \JzIT\Config\Exception\ConfigKeyNotFoundException
     * @throws \JzIT\Config\Exception\NotSupportedConfigValueTypeException
     */
    public function get(string $key, $default = null)
    {
        if ($default !== null && !$this->hasValue($key)) {
            return $default;
        }

        if (!$this->hasValue($key)) {
            throw new ConfigKeyNotFoundException(sprintf('Could not find key "%s" in "%s"', $key, __CLASS__));
        }

        return $this->getValue($key);
    }

    /**
     * @param string $key
     *
     * @return mixed
     *
     * @throws \JzIT\Config\Exception\NotSupportedConfigValueTypeException
     */
    protected function getValue(string $key)
    {
        return $this->config[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function hasValue(string $key): bool
    {
        return isset($this->config[$key]);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    protected function initialize(): void
    {
        $config = new ArrayObject();

        $this->buildConfig($config);
        $this->buildConfig($config, $this->environment);

        $this->config = $config;
    }

    /**
     * @param \ArrayObject $config
     * @param string $environment
     *
     * @return \ArrayObject
     */
    protected function buildConfig(ArrayObject $config, string $environment = null): ArrayObject
    {
        $configFile = $environment ?? self::CONFIG_FILE;
        $fileName = self::CONFIG_FILE_PREFIX . $configFile . self::CONFIG_FILE_SUFFIX;
        $pathToConfigFile = $this->appDir . $fileName;

        if (\file_exists($pathToConfigFile)) {
            include $pathToConfigFile;
        }

        return $config;
    }
}
