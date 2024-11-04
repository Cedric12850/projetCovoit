<?php

namespace App\Controller\Admin;

use App\Entity\Specificity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpecificityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Specificity::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Name')
                ->setLabel('Spécificité'),
           BooleanField::new('active')
                ->setLabel('Active'),
        ];
    }
}
