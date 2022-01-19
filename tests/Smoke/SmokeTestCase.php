<?php

declare(strict_types=1);

namespace App\Tests\Smoke;

use App\Domain\Security\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Generator;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class SmokeTestCase extends WebTestCase
{
    /**
     * @return Generator<string, array{path: string, email: string|null}>
     */
    abstract public function provideRoutes(): Generator;

    /**
     * @test
     *
     * @dataProvider provideRoutes
     */
    public function shouldReturnSuccessfulResponse(string $path, ?string $email = null): void
    {
        $client = self::createClient();

        if (null !== $email) {
            /** @var UserRepository<User> $userRepository */
            $userRepository = $client->getContainer()->get(UserRepository::class);

            /** @var UserInterface|null $user */
            $user = $userRepository->loadUserByIdentifier($email);

            if (null === $user) {
                throw new RuntimeException(sprintf('The user %s does not exist.', $email));
            }

            $client->loginUser($user);
        }

        $client->request(Request::METHOD_GET, $path);

        self::assertResponseIsSuccessful();
    }
}
