<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            TextField::new('FirstName'),
            TextField::new('LastName'),
            TextField::new('pseudo'),
            TextField::new('password'),
            IntegerField::new('type_user'),
            AssociationField::new('town_id', 'Town'),
            ImageField::new('photo')
                ->setBasePath('uploads/')
                ->setUploadDir('assets/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
            ,
            TextField::new('address'),
            IntegerField::new('zip_code'),
            BooleanField::new('driving_license'),
            BooleanField::new('auto_accept'),
            BooleanField::new('active')
        ];
    }
    
}
