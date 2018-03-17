<?php declare(strict_types=1);

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\ShareNetworkPass;
use AppBundle\DependencyInjection\Compiler\WidgetPass;
use AppBundle\Share\Network\ShareNetworkInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new WidgetPass());
        $container->addCompilerPass(new ShareNetworkPass());

        $container->registerForAutoconfiguration(ShareNetworkInterface::class)
            ->addTag('share.network')
        ;
    }
}
