<?php

declare(strict_types=1);

namespace App\Core\Http\Request\ParamConverter;

use App\Core\Bus\Command\CommandInterface;
use App\Core\Bus\Query\QueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

final class PostConverter implements ParamConverterInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return false;
        }

        $object = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');

        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        $interfaces = class_implements($configuration->getClass());

        return is_array($interfaces)
            && count(array_intersect([QueryInterface::class, CommandInterface::class], $interfaces)) > 0;
    }
}
