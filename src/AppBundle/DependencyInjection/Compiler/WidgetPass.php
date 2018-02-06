<?php declare(strict_types=1);

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Widget\WidgetPreparer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class WidgetPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(WidgetPreparer::class)) {
            return;
        }

        $widgetPreparer = $container->findDefinition(WidgetPreparer::class);

        $taggedServices = $container->findTaggedServiceIds('widget.factory');

        foreach ($taggedServices as $id => $tags) {
            $widgetPreparer->addMethodCall('addWidgetFactory', [new Reference($id)]);
        }
    }
}
