<?php

namespace App\Controller\Admin;

use App\Entity\Section;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class SectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Section::class;
    }

    public function configureFields(string $pageName): iterable
    {

        switch ($pageName) {
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    TextareaField::new('texte'),
                    AssociationField::new('chapitre'),
                    AssociationField::new('ressources', 'Ressources')->setFormTypeOption("by_reference", false),
                ];
            default:
                return [
                    TextareaField::new('texte'),
                    AssociationField::new('chapitre'),
                    AssociationField::new('ressources', 'Ressources')->setFormTypeOption("by_reference", false),
                ];
        }
    }
}
