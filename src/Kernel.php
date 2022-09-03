<?php

namespace IncentiveFactory\IlEtaitUneFoisUnDev;

use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Type\AbstractEnumType;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    /**
     * @see https://smaine-milianni.medium.com/use-php-enums-as-doctrine-type-in-symfony-85909aa0a19a
     */
    public function process(ContainerBuilder $container): void
    {
        $typesDefinition = $container->getParameter('doctrine.dbal.connection_factory.types');

        $taggedEnums = $container->findTaggedServiceIds('app.doctrine_enum_type');

        foreach ($taggedEnums as $enumType => $definition) {
            /* @var $enumType AbstractEnumType */
            $typesDefinition[$enumType::NAME] = ['class' => $enumType];
        }

        $container->setParameter('doctrine.dbal.connection_factory.types', $typesDefinition);
    }
}
