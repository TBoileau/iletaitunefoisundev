<?php

declare(strict_types=1);

namespace App\Admin\Form;

use App\Content\Entity\Answer;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AnswerType extends AbstractType
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Intitulé',
                'empty_data' => '',
            ])
            ->add('content', TextEditorType::class, [
                'label' => 'Contenu',
            ])
            ->add('good', CheckboxType::class, [
                'label' => 'Bonne réponse ?',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Answer::class);
    }
}
