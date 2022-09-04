<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\DependencyInjection\CompilerPass;

use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Type\AbstractEnumType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DoctrinePass implements CompilerPassInterface
{
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
