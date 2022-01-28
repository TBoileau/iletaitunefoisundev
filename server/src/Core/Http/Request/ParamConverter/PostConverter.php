<?php

declare(strict_types=1);

namespace App\Core\Http\Request\ParamConverter;

use App\Core\CQRS\MessageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PostConverter implements ParamConverterInterface
{
    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validator)
    {
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return false;
        }

        $object = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');

        $constraintViolationList = $this->validator->validate($object);

        if ($constraintViolationList->count() > 0) {
            throw new ValidationFailedException($object, $constraintViolationList);
        }

        $request->attributes->set($configuration->getName(), $object);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        $interfaces = class_implements($configuration->getClass());

        return is_array($interfaces) && in_array(MessageInterface::class, $interfaces, true);
    }
}
