<?php

namespace App\Form;

use App\Entity\Biens;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categories;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddBiensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix')
            ->add('surface')
            ->add('type')
            ->add('localisation')
            ->add('intitule')
            ->add('descriptif')
            ->add('reference')
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'mapped' => false,
                'choice_label' => 'libelle',
            ])
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Biens::class,
        ]);
    }
}
