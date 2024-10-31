<?php

namespace App\Controller\Admin;

use App\Entity\Step;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StepCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Step::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('trip','Trip')
                ->setLabel('Trajet'),
            IntegerField::new('num_order')
                ->setLabel('No'),
            AssociationField::new('town_step','Town')
                ->setLabel('Ville'),
            TextField::new('place_step')
                ->setLabel('Complément'),
            NumberField::new('price_passenger')
                ->setLabel('Prix par passager')
                ->setNumDecimals(2)
                ->setStoredAsString(false),
            IntegerField::new('duration')
                ->setLabel('Durée'),
            IntegerField::new('length_km')
                ->setLabel('Km'),
        ];
    }
}
