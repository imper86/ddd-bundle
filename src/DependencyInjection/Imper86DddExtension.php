<?php

declare(strict_types=1);

namespace Imper86\DddBundle\DependencyInjection;

use Imper86\DDD\Domain\Bus\Command\CommandHandler;
use Imper86\DDD\Domain\Bus\Event\EventHandler;
use Imper86\DDD\Domain\Bus\Query\QueryHandler;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class Imper86DddExtension extends Extension implements
    PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . "/../Resources/config")
        );
        $loader->load("services.yaml");
    }

    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension("framework")) {
            $container->prependExtensionConfig("framework", [
                "messenger" => [
                    "default_bus" => "command.bus",
                    "buses" => [
                        "command.bus" => null,
                        "event.bus" => [
                            "default_middleware" => "allow_no_handlers",
                        ],
                        "query.bus" => null,
                    ],
                ],
            ]);
        }

        $container
            ->registerForAutoconfiguration(CommandHandler::class)
            ->addTag("messenger.message_handler", ["bus" => "command.bus"]);

        $container
            ->registerForAutoconfiguration(EventHandler::class)
            ->addTag("messenger.message_handler", ["bus" => "event.bus"]);

        $container
            ->registerForAutoconfiguration(QueryHandler::class)
            ->addTag("messenger.message_handler", ["bus" => "query.bus"]);
    }
}
