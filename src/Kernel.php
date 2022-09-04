<?php

namespace IncentiveFactory\IlEtaitUneFoisUnDev;

use IncentiveFactory\IlEtaitUneFoisUnDev\DependencyInjection\CompilerPass\DoctrinePass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new DoctrinePass());
    }
}
