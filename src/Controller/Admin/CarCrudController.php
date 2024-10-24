<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('owner', 'User')
                ->setLabel('Propriétaire'),
            TextField::new('brand')
                ->setLabel('Marque'),
            TextField::new('type_car')
                ->setLabel('Type'),
            BooleanField::new('active')
                ->setLabel('Actif'),
            AssociationField::new('specificities', 'Specificity')
                ->setLabel('Spécificités'),
        ];
    }
}
