<?php

declare(strict_types=1);

namespace IncentiveFactory\IlEtaitUneFoisUnDev\Form;

use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Player\Register\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email :',
                'attr' => [
                    'placeholder' => 'Votre adresse email',
                ],
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Pseudo :',
                'attr' => [
                    'placeholder' => 'Votre pseudo',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe :',
                'attr' => [
                    'placeholder' => 'Votre mot de passe',
                ],
            ])
            ->add('gender', EnumType::class, [
                'label' => 'Vous êtes :',
                'class' => Gender::class,
                'choice_label'=> static fn (Gender $choice): string => $choice->value
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Registration::class);
    }
}
