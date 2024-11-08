<?php

namespace App\Form;

use App\Entity\Step;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Doctrine\DBAL\Types\TextType;
// use Doctrine\DBAL\Types\IntegerType;
// Erreurs  : Could not load type "Doctrine\DBAL\Types\IntegerType": class does not implement "Symfony\Component\Form\FormTypeInterface".
// Correction : utiliser les types de Doctrine DBAL au lieu des types de formulaire Symfony. 
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class StepSsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, 
                              array $options): void
    {
        $builder
        ->add('num_order', IntegerType::class, ['label' => 'Ordre'])
        ->add('town_step', EntityType::class, [
            'class' => Town::class,
            'choice_label' => 'name',
            'label' => 'Ville'
        ])
        ->add('place_step', TextType::class, ['label' => 'Lieu'])
        ->add('price_passenger', NumberType::class, ['label' => 'Prix'])
        ->add('length_km', IntegerType::class, ['label' => 'Distance (km)'])
        ->add('duration', IntegerType::class, ['label' => 'Durée']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
