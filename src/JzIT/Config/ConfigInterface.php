<?php

declare(strict_types=1);

namespace JzIT\Config;

interface ConfigInterface
{
    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);
}
