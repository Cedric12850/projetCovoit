<?php

namespace App\Form;

use App\Entity\Town;
use App\Repository\TownRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TownAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Town::class,
            'placeholder' => 'Choisissez une ville dans la liste',
            'choice_label' => 'name',

            'query_builder' => function(TownRepository $townRepository) {
                return $townRepository->createQueryBuilder('town');
            },
            // choose which fields to use in the search
            // if not passed, *all* fields are used
            // 'searchable_fields' => ['name'],

            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
