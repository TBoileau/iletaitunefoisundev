<?php

declare(strict_types=1);

namespace App\Tests\Integration\Form;

use App\Admin\Form\YoutubeType;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Forms;

final class YoutubeTypeTest extends KernelTestCase
{
    /**
     * @test
     */
    public function shouldBeSynchronizedAndValid(): void
    {
        self::bootKernel();

        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();

        $formData = 'https://www.youtube.com/watch?v=-S94RNjjb4I';

        $form = $formFactory->create(YoutubeType::class, '-S94RNjjb4I');

        $form->submit($formData);
        self::assertTrue($form->isSynchronized());
        self::assertTrue($form->isValid());
        self::assertEquals('-S94RNjjb4I', $form->getData());
    }

    /**
     * @test
     */
    public function shouldBeNotSynchronized(): void
    {
        self::bootKernel();

        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();

        $formData = 'fail';

        $form = $formFactory->create(YoutubeType::class);

        $form->submit($formData);
        self::assertFalse($form->isSynchronized());
    }

    /**
     * @test
     */
    public function shouldRaiseRuntimeException(): void
    {
        self::bootKernel();

        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();

        $formData = null;

        $form = $formFactory->create(YoutubeType::class);

        $this->expectException(RuntimeException::class);

        $form->submit($formData);
    }
}
