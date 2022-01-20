<?php

declare(strict_types=1);

namespace App\Tests\Component;

use App\Domain\Node\Message\Link;
use App\Domain\Security\Message\Registration;
use App\Domain\Security\Validator\UniqueEmail;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotIdenticalTo;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidationTest extends KernelTestCase
{
    /**
     * @test
     *
     * @param array<string, array<array-key, class-string<MetadataInterface>>> $expectedConstraints
     *
     * @dataProvider provideMetadataByEntity
     */
    public function shouldHaveValidationMetadata(string $class, array $expectedConstraints): void
    {
        self::bootKernel();

        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get(ValidatorInterface::class);

        /**
         * @var array<string, array<array-key, MetadataInterface>> $mapping
         * @phpstan-ignore-next-line
         */
        $mapping = $validator->getMetadataFor($class)->members;

        /** @var array<string, array<array-key, class-string>> $constraints */
        $constraints = [];

        foreach ($mapping as $name => $item) {
            foreach ($item as $metadata) {
                $constraints[$name] = array_map('get_class', $metadata->getConstraints());
            }
            sort($constraints[$name]);
        }

        self::assertEquals($expectedConstraints, $constraints);
    }

    /**
     * @return Generator<string, array<string, mixed>>
     */
    public function provideMetadataByEntity(): Generator
    {
        yield 'registration' => [
            'class' => Registration::class,
            'constraints' => [
                'email' => [
                    UniqueEmail::class,
                    Email::class,
                    NotBlank::class,
                ],
                'plainPassword' => [
                    NotBlank::class,
                    Regex::class,
                ],
            ],
        ];

        yield 'link' => [
            'class' => Link::class,
            'constraints' => [
                'from' => [
                    NotIdenticalTo::class,
                    NotNull::class,
                ],
                'to' => [
                    NotNull::class,
                ],
            ],
        ];
    }
}
