<?php

namespace App\Controller\Admin;

use App\Entity\Chapitre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ChapitreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chapitre::class;
    }



    public function configureFields(string $pageName): iterable
    {

        switch ($pageName) {
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    TextField::new('titre'),
                    AssociationField::new('ouvrage'),
                    AssociationField::new('ressources', 'Ressources')->setFormTypeOption("by_reference", false)
                ];
            default:
                return [
                    TextField::new('titre'),
                    AssociationField::new('ouvrage'),
                    AssociationField::new('sections', 'Sections')->setFormTypeOption("by_reference", false),
                    AssociationField::new('ressources', 'Ressources')->setFormTypeOption("by_reference", false)
                ];
        }
    }
    
}
