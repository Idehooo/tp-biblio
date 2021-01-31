<?php

namespace App\Controller\Admin;

use App\Entity\Ouvrage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class OuvrageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ouvrage::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        $imageView = ImageField::new('couverture')->setBasePath('medias/images')->setLabel('Couverture');
        $imageEdit = TextareaField::new('couvertureFile')->setFormType(VichImageType::class);

        switch ($pageName) {
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    TextField::new('titre'),
                    TextField::new('auteur'),
                    AssociationField::new('collectionOuvrage'),
                    AssociationField::new('ressources', 'Ressources')->setFormTypeOption("by_reference", false),
                    $imageEdit
                ];
            default:
                return [
                    TextField::new('titre'),
                    TextField::new('auteur'),
                    AssociationField::new('collectionOuvrage'),
                    $imageView
                ];
        }
    }
    
}

