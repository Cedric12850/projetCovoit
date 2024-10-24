<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user','User')
                ->setLabel('Par'),
            AssociationField::new('step','Step')
                ->setLabel('Etape'),
            IntegerField::new('nb_place')
                ->setLabel('Nb places'),
            BooleanField::new('notify')
                ->setLabel('Notif Conduct'),
            BooleanField::new('Accept')
                ->setLabel('Accpetée'),
            BooleanField::new('notify')
                ->setLabel('Annulation'),
        ];
    }
}
