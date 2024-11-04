<?php

namespace App\Controller\Admin;

use App\Entity\Town;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TownCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Town::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')
                ->setLabel('Ville'),
                TextField::new('zip_code')
                    ->setLabel('CP'),
                TextField::new('department')
                    ->setLabel('department'),
                TextField::new('INSEE')
                    ->setLabel('INSEE'),
    ];
    }
}
