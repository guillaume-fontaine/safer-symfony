<?php

namespace App\Form;

use App\Entity\Favoris;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteFavorisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('favoris', EntityType::class, [
                'class' => Favoris::class,
                'mapped' => false,
                'choice_label' => function ($category) {
                return $category->getMail().' - '.$category->getDate()->format('d/m/Y');
            }
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Favoris::class,
        ]);
    }
}
