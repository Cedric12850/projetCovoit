<?php


namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Town;

class TripSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('town_start', EntityType::class, [
                'class' => Town::class,
                'label' => 'Ville de départ',
            ])
            ->add('town_end', EntityType::class, [
                'class' => Town::class,
                'label' => 'Ville d\'arrivée',
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
        ]);
    }
}
