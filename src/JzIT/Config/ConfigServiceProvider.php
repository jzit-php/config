<?php

declare(strict_types=1);

namespace JzIT\Config;

use Di\Container;
use JzIT\Container\ServiceProvider\AbstractServiceProvider;

class ConfigServiceProvider extends AbstractServiceProvider
{
    /**
     * @param \Di\Container $container
     *
     * @return void
     */
    public function register(Container $container): void
    {
        $self = $this;

        $container->set('config', function (Container $container) use ($self) {
            return $self->createConfig($container);
        });
    }

    /**
     * @param \Di\Container $container
     *
     * @return \JzIT\Config\ConfigInterface
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function createConfig(Container $container): ConfigInterface
    {
        $appDir = $container->get('app_dir');
        $environment = $container->get('environment');

        return new Config($appDir, $environment);
    }
}
