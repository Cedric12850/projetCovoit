<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TripCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trip::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //TextField::new('date_start'),
            DateTimeField::new('date_start')
                ->setFormat('yyyy-MM-dd HH:mm')
                ->renderAsNativeWidget()
                ->setLabel('Départ le'),
            AssociationField::new('town_start','Town')
                ->setLabel('Ville'),
            TextField::new('place_start')
                ->setLabel('Complément'),
            AssociationField::new('car','Car')
                ->setLabel('Voiture'),
            AssociationField::new('driver','User')
                ->setLabel('Conducteur'),
            IntegerField::new('nb_passenger')
                ->setLabel('Nb places'),
            Integerfield::new('frequency')
                ->setLabel('Fréq'),
            BooleanField::new('comfort')
                ->setLabel('Confort'),
            BooleanField::new('cancel')
                ->setLabel('Annuler'),
        ];
    }

}
