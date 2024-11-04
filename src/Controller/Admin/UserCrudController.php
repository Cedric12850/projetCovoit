<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
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
            TextField::new('pseudo'),
            TextField::new('LastName')
                ->setLabel('Nom'),
            TextField::new('FirstName')
                ->setLabel('Prenom'),
            EmailField::new('email')
                ->setLabel('Mail'),
            TextField::new('password')
                ->setLabel('MdP'),
            AssociationField::new('town', 'Town')
                ->setLabel('Ville'),
            ImageField::new('photo')
                ->setBasePath('uploads/')
                ->setUploadDir('assets/uploads/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setLabel('Photo'),
            TextField::new('address')
                ->setLabel('Adresse'),
            BooleanField::new('driving_license')
                ->setLabel('Permis valide'),
            BooleanField::new('auto_accept')
                ->setLabel('Accept Autom résa'),
            BooleanField::new('active')
                ->setLabel('Actif'),
            ArrayField::new('roles')
                ->setLabel('Rôles'),
            IntegerField::new('type_user')
                ->setLabel('Type'),
            IntegerField::new('zip_code'),
            TelephoneField::new('phone')
                ->setLabel('Téléphone'),
        ];
    }
    
}
