<?php

namespace App\Form;

use App\Form\TownAutocompleteField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('town_start', TownAutocompleteField::class, [
                'attr' => [
                'data-autocomplete-url' => $options['autocomplete_url'],
                ],
                'placeholder' => 'Choisissez votre ville de départ',
            ])
            ->add('town_end', TownAutocompleteField::class, [
                'attr' => [
                'data-autocomplete-url' => $options['autocomplete_url'],
                ],
                'placeholder' => 'Choisissez votre ville de départ',
            ])
            ->add('date_start', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
            ])
            ->add('nb_passenger', ChoiceType::class, [
                'label' => 'Nombre de passagers',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                ],
                'expanded' => false,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Si vous avez une classe de données spécifique, définissez-la ici
            // 'data_class' => YourDataClass::class,
            'autocomplete_url' => null,
        ]);
    }
}

