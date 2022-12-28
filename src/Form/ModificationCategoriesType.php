<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ModificationCategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('categories', EntityType::class, [
            'class' => Categories::class,
            'mapped' => false,
            'choice_label' => 'libelle',
        ])
        ->add('editLibelle', TextType::class, [
            'label' => 'Nouveau libelle',
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer une catégorie',
                ]),
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
