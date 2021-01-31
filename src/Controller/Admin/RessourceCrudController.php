<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RessourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ressource::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageView = ImageField::new('vignette')->setBasePath('medias/images')->setLabel('Vignette');
        $imageEdit = TextareaField::new('vignetteFile')->setFormType(VichImageType::class);

        switch ($pageName) {
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    TextField::new('nom'),
                    $imageEdit
                ];
            default:
                return [
                    TextField::new('nom'),
                    $imageView,
                    AssociationField::new('ouvrage'),
                    AssociationField::new('chapitre'),
                    AssociationField::new('section'),
                ];
        }
    }
}
