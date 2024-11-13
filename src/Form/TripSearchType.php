<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/* #[AsEntityAutocompleteField()] */
class TripSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('town_start', TextType::class, [
            'label' => 'Ville de départ',
            'attr' => [
                'class' => 'town_autocomplete',
                'placeholder' => 'Choisissez votre ville de départ'
            ],
            'required' => true
            ])
            ->add('town_start_id', HiddenType::class) // Champ caché pour l'ID de la ville de départ

            ->add('town_end', TextType::class, [
            'label' => 'Ville d\'arrivée',
            'attr' => [
                'class' => 'town_autocomplete', // Classe pour le JS d'autocomplétion
                'placeholder' => 'Choisissez votre ville d\'arrivée'
            ],
            'required' => true
            ])
            ->add('town_end_id', HiddenType::class) // Champ caché pour l'ID de la ville d'arrivée

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
        ]);
    }
}
