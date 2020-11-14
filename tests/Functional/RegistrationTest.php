<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class RegistrationTest extends WebTestCase
{
    public function testIfRegistrationIsSuccessful(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $router->generate("registration"));

        $form = $crawler->filter("form[name=registration]")->form([
            "registration[email]" => "user+new@email.com",
            "registration[plainPassword]" => "password",
            "registration[nickname]" => "user+new"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertRouteSame('index');
    }

    /**
     * @dataProvider provideInvalidForm
     */
    public function testIfFormIsInvalid(array $formData, string $errorMessage): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $router->generate("registration"));

        $form = $crawler->filter("form[name=registration]")->form($formData);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains(".form-error-message", $errorMessage);
    }

    public function provideInvalidForm(): iterable
    {
        yield [
            [
                "registration[email]" => "",
                "registration[plainPassword]" => "password",
                "registration[nickname]" => "user+new"
            ],
            "Cette valeur ne doit pas être vide."
        ];

        yield [
            [
                "registration[email]" => "user+new@email.com",
                "registration[plainPassword]" => "",
                "registration[nickname]" => "user+new"
            ],
            "Cette valeur ne doit pas être vide."
        ];

        yield [
            [
                "registration[email]" => "user+new@email.com",
                "registration[plainPassword]" => "password",
                "registration[nickname]" => ""
            ],
            "Cette valeur ne doit pas être vide."
        ];

        yield [
            [
                "registration[email]" => "user+new@email.com",
                "registration[plainPassword]" => "fail",
                "registration[nickname]" => "user+new"
            ],
            "Cette chaîne est trop courte. Elle doit avoir au minimum 8 caractères."
        ];

        yield [
            [
                "registration[email]" => "user+new@email.com",
                "registration[plainPassword]" => "fail",
                "registration[nickname]" => "user+new"
            ],
            "Cette chaîne est trop courte. Elle doit avoir au minimum 8 caractères."
        ];

        yield [
            [
                "registration[email]" => "admin@email.com",
                "registration[plainPassword]" => "password",
                "registration[nickname]" => "user+new"
            ],
            "Cette valeur est déjà utilisée."
        ];

        yield [
            [
                "registration[email]" => "user+new@email.com",
                "registration[plainPassword]" => "password",
                "registration[nickname]" => "admin"
            ],
            "Cette valeur est déjà utilisée."
        ];
    }

    public function testIfCsrfTokenIsInvalid(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $router->generate("registration"));

        $form = $crawler->filter("form[name=registration]")->form([
            "registration[_token]" => "fail",
            "registration[email]" => "user+new@email.com",
            "registration[plainPassword]" => "password",
            "registration[nickname]" => "user+new"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains(
            ".form-error-message",
            "Le jeton CSRF est invalide. Veuillez renvoyer le formulaire."
        );
    }
}
