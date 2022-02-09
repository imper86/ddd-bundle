<?php

declare(strict_types = 1);

namespace Imper86\DddBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class Imper86DddExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->prependExtensionConfig(
            'FrameworkExtension',
            [
                'framework' => [
                    'messenger' => [
                        'default_bus' => 'command.bus',
                        'buses' => [
                            'command.bus' => null,
                            'event.bus' => [
                                'default_middleware' => 'allow_no_handlers',
                            ],
                            'query.bus' => null,
                        ],
                    ],
                ],
            ],
        );
    }
}
