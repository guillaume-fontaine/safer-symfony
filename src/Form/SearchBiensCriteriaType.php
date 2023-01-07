<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBiensCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prix_min', NumberType::class, ['required' => false, 'label' => 'Prix minimum'])//aurait ete non mappe
        ->add('prix_max', NumberType::class, ['required' => false, 'label' => 'Prix maximum'])//aurait ete non mappe
        ->add('localisation', TextType::class, ['required' => false, 'label' => 'Localisation'])//aurait ete mappe
        ->add('mot_clefs', TextType::class, ['required' => false, 'label' => 'Mots-clés'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
