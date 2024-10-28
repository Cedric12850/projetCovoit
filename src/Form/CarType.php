<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Specificity;
use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TextType::class,[
                'label' => 'Marque',
            ])
            ->add('type_car', TextType::class, [
                'label' => 'Modèle'
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Véhicule utilisé'
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
            ])
            ->add('specificities', EntityType::class, [
                'class' => Specificity::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
