<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('creator','User')
                ->setLabel('Créé par'),
            AssociationField::new('trip','Trip')
                ->setLabel('trajet'),
            AssociationField::new('concern','User')
                ->setLabel('Concerne'),
            TextEditorField::new('comment')
                ->setLabel('Commentaire'),
            TextEditorField::new('response')
                ->setLabel('Réponse'),
            IntegerField::new('notation')
                ->setLabel('Note')
        ];
    }
}
