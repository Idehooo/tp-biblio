<?php

namespace App\Controller\Admin;

use App\Entity\CollectionOuvrage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CollectionOuvrageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CollectionOuvrage::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre')
        ];
    }
}
