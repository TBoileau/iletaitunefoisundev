<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer;

use IncentiveFactory\Domain\Player\Player as DomainPlayer;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player as EntityPlayer;

/**
 * @template-implements EntityTransformer<DomainPlayer, EntityPlayer>
 */
final class PlayerTransformer implements EntityTransformer
{
    /**
     * @param EntityPlayer $entity
     */
    public function transform($entity): DomainPlayer
    {
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
     * @param DomainPlayer  $entity
     * @param ?EntityPlayer $target
     */
    public function reverseTransform($entity, $target = null): EntityPlayer
    {
        if (null === $target) {
            $target = new EntityPlayer();
        }

        return $target
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
