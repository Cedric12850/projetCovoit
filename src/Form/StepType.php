<?php

namespace App\Form;

use App\Entity\Step;
use App\Entity\Town;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, 
                              array $options): void
    {
        $builder
            ->add('trip', EntityType::class, [
                'class' => Trip::class,
                'choice_label' => 'id',
            ])
            ->add('num_order')
            ->add('town_step', EntityType::class, [
                'class' => Town::class,
                'choice_label' => 'id',
            ])
            ->add('place_step')
            ->add('price_passenger')
            ->add('length_km')
            ->add('duration');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}