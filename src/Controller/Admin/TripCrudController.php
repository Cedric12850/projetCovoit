<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use App\Form\StepSsFormType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
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

            // Ajouter la saisie d'une étape => la rendre obligatoire
            CollectionField::new('steps')
                ->setEntryType(StepSsFormType::class)
                ->setLabel('Étapes')
                ->allowAdd()
                ->allowDelete()
                ->setRequired(true),
        ];
    }
}