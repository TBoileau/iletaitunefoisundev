<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Form;

use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Player\UpdateProfile\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\UX\Dropzone\Form\DropzoneType;

final class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email :',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Votre adresse email',
                ],
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo :',
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Votre pseudo',
                ],
            ])
            ->add('gender', EnumType::class, [
                'label' => 'Vous êtes :',
                'class' => Gender::class,
                'choice_label' => static fn (Gender $choice): string => $choice->value,
            ])
            ->add('avatarFile', DropzoneType::class, [
                'label' => 'Avatar :',
                'required' => false,
                'mapped' => false,
                'constraints' => [new Image()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Profile::class);
    }
}
