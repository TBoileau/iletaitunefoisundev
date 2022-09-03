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
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.email = :email')
            ->setParameter('email', $email);

        if (null !== $player) {
            $queryBuilder->andWhere('p.id != :id')->setParameter('id', $player->id());
        }

        /** @var int $numberOfUsers */
        $numberOfUsers = $queryBuilder->getQuery()->getSingleScalarResult();

        return $numberOfUsers > 0;
    }

    public function hasRegistrationToken(string $registrationToken): bool
    {
        return $this->count(['registrationToken' => $registrationToken]) > 0;
    }

    public function findOneByEmail(string $email): ?DomainPlayer
    {
        $playerEntity = $this->findOneBy(['email' => $email]);

        if (null === $playerEntity) {
            return null;
        }

        return $this->playerTransformer->transform($playerEntity);
    }

    public function findOneByRegistrationToken(string $registrationToken): ?DomainPlayer
    {
        $playerEntity = $this->findOneBy(['registrationToken' => $registrationToken]);

        if (null === $playerEntity) {
            return null;
        }

        return $this->playerTransformer->transform($playerEntity);
    }

    public function findOneByForgottenPasswordToken(string $forgottenPasswordToken): ?DomainPlayer
    {
        // TODO: Implement findOneByForgottenPasswordToken() method.
        return null;
    }

    public function update(DomainPlayer $player): void
    {
        $playerEntity = $this->find($player->id());
        $this->playerTransformer->reverseTransform($player, $playerEntity);
        $this->_em->flush();
    }
}
