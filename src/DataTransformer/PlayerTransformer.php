<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\DataTransformer;

use IncentiveFactory\Domain\Player\Player as DomainPlayer;
use IncentiveFactory\IlEtaitUneFoisUnDev\Entity\Player as EntityPlayer;

/**
 * @template-implements EntityTransformer<EntityPlayer, DomainPlayer>
 */
final class PlayerTransformer implements EntityTransformer
{
    /**
     * @param EntityPlayer|null $entity
     */
    public function transform($entity): ?DomainPlayer
    {
        if (null === $entity) {
            return null;
        }

        return DomainPlayer::create(
            $entity->getId(),
            $entity->getEmail(),
            $entity->getGender(),
            $entity->getNickname(),
            $entity->getPassword(),
            $entity->getAvatar(),
            $entity->getRegistrationToken(),
            $entity->getRegisteredAt(),
            $entity->getForgottenPasswordToken(),
            $entity->getForgottenPasswordExpiredAt()
        );
    }

    /**
     * @param DomainPlayer|null $entity
     */
    public function reverseTransform($entity): ?EntityPlayer
    {
        if (null === $entity) {
            return null;
        }

        return (new EntityPlayer())
            ->setId($entity->id())
            ->setGender($entity->gender())
            ->setEmail($entity->email())
            ->setNickname($entity->nickname())
            ->setPassword($entity->password())
            ->setAvatar($entity->avatar())
            ->setRegistrationToken($entity->registrationToken())
            ->setRegisteredAt($entity->registeredAt())
            ->setForgottenPasswordToken($entity->forgottenPasswordToken())
            ->setForgottenPasswordExpiredAt($entity->forgottenPasswordExpiredAt());
    }
}
