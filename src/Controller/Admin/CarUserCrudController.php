<?php

namespace App\Controller\Admin;

use App\Entity\CarUser;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class CarUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CarUser::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('car', 'Car')
                ->setLabel('Voiture'),
            AssociationField::new('driver', 'User')
                ->setLabel('Conducteur'),
            BooleanField::new('active')
                ->setLabel('Actif')
        ];
    }
    
}
