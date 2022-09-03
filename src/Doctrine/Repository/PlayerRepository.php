<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use IncentiveFactory\Domain\Player\Player as DomainPlayer;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\DataTransformer\PlayerTransformer;
use IncentiveFactory\IlEtaitUneFoisUnDev\Doctrine\Entity\Player as EntityPlayer;

/**
 * @template-extends ServiceEntityRepository<EntityPlayer>
 */
final class PlayerRepository extends ServiceEntityRepository implements PlayerGateway
{
    public function __construct(ManagerRegistry $registry, private PlayerTransformer $playerTransformer)
    {
        parent::__construct($registry, EntityPlayer::class);
    }

    public function register(DomainPlayer $player): void
    {
        $this->_em->persist($this->playerTransformer->reverseTransform($player));
        $this->_em->flush();
    }

    public function hasEmail(string $email, ?DomainPlayer $player = null): bool
    {
        // TODO: Implement hasEmail() method.
        return false;
    }

    public function hasRegistrationToken(string $registrationToken): bool
    {
        // TODO: Implement hasRegistrationToken() method.
        return false;
    }

    public function findOneByEmail(string $email): ?DomainPlayer
    {
        // TODO: Implement findOneByEmail() method.
        return null;
    }

    public function findOneByRegistrationToken(string $registrationToken): ?DomainPlayer
    {
        // TODO: Implement findOneByRegistrationToken() method.
        return null;
    }

    public function findOneByForgottenPasswordToken(string $forgottenPasswordToken): ?DomainPlayer
    {
        // TODO: Implement findOneByForgottenPasswordToken() method.
        return null;
    }

    public function update(DomainPlayer $player): void
    {
        // TODO: Implement update() method.
    }
}
