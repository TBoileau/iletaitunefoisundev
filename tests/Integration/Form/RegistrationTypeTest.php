<?php

declare(strict_types=1);

namespace App\Tests\Integration\Form;

use App\Domain\Security\Message\Registration;
use App\UserInterface\Form\RegistrationType;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegistrationTypeTest extends KernelTestCase
{
    /**
     * @test
     */
    public function shouldBeSynchronizedAndValid(): void
    {
        self::bootKernel();

        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();

        $formData = [
            'email' => 'user+6@email.com',
            'plainPassword' => 'password',
        ];

        $registration = new Registration();

        $form = $formFactory->create(RegistrationType::class, $registration);

        $expectedRegistration = new Registration();
        $expectedRegistration->setEmail('user+6@email.com');
        $expectedRegistration->setPlainPassword('password');

        $formView = $form->createView();
        self::assertArrayHasKey('email', $formView->children);
        self::assertArrayHasKey('plainPassword', $formView->children);

        $form->submit($formData);
        self::assertTrue($form->isSynchronized());
        self::assertTrue($form->isValid());
        self::assertEquals($expectedRegistration, $registration);
    }

    /**
     * @test
     *
     * @param array<string, string> $formData
     *
     * @dataProvider provideInvalidData
     */
    public function shouldBeSynchronizedAndInvalid(array $formData): void
    {
        self::bootKernel();

        /** @var ValidatorInterface $validator */
        $validator = self::getContainer()->get(ValidatorInterface::class);

        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();

        $registration = new Registration();

        $form = $formFactory->create(RegistrationType::class, $registration);

        $formView = $form->createView();
        self::assertArrayHasKey('email', $formView->children);
        self::assertArrayHasKey('plainPassword', $formView->children);

        $form->submit($formData);
        self::assertTrue($form->isSynchronized());
        self::assertFalse($form->isValid());
    }

    /**
     * @return Generator<string, array<array-key, array<string, string>>>
     */
    public function provideInvalidData(): Generator
    {
        yield 'wrong email' => [self::createData(['email' => 'fail'])];
        yield 'empty email' => [self::createData(['email' => ''])];
        yield 'non unique email' => [self::createData(['email' => 'user+1@email.com'])];
        yield 'wrong password' => [self::createData(['plainPassword' => 'fail'])];
        yield 'empty password' => [self::createData(['plainPassword' => ''])];
    }

    /**
     * @param array<string, string> $extra
     *
     * @return array<string, string>
     */
    private static function createData(array $extra = []): array
    {
        return $extra + [
                'email' => 'user+6@email.com',
                'plainPassword' => 'password',
            ];
    }
}
