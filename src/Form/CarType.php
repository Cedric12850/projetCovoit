<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Specificity;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', TypeTextType::class, [
                'label' => 'Marque'
            ])
            ->add('type_car', TypeTextType::class, [
                'label' => 'Modèle'
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'cochez si utilisé'
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'label' => 'propriétaire',
                'choice_label' => function ($userIdentity) {return $userIdentity;},
            ])
            ->add('specificities', EntityType::class, [
                'class' => Specificity::class,
                'label' => 'préférences de voyage',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.name', 'ASC');}
            ])
            ->add ('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
