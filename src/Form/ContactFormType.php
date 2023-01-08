<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Veuillez rentrer plus de texte.',
                        'max' => 400,
                        'maxMessage' => 'Cette valeur est trop longue. Elle devrait comporter {{ limit }} caractères ou moins.',
                    ]),
                ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
