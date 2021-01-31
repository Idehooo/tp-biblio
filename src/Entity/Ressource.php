<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity(repositoryClass=RessourceRepository::class)
 * 
 * @Vich\Uploadable
 */
class Ressource
{
    const SERVER_PATH_TO_IMG_FOLDER = 'medias/images';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vignette;

    /**
     * @Vich\UploadableField(mapping="vignetteFile", fileNameProperty="vignette")
     * @var File
     */
    private $vignetteFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fichier;

    /**
     * @ORM\ManyToOne(targetEntity=Chapitre::class, inversedBy="ressources", cascade={"persist"})
     */
    private $chapitre;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="ressources", cascade={"persist"})
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity=Ouvrage::class, inversedBy="ressources")
     */
    private $ouvrage;

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVignette(): ?string
    {
        return $this->vignette;
    }

    public function setVignette(?string $vignette): self
    {
        $this->vignette = $vignette;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getVignetteFile()
    {
        return $this->vignetteFile;
    }

    /**
     * @param UploadedFile $vignetteFile
     * @param bool $delete
     * @throws Exception
     */
    public function setVignetteFile($vignetteFile, $delete = true)
    {
        if ($delete) {
            $uniqueName = $this->generateUniqueName( $vignetteFile );
            if ($uniqueName != null) {
                $vignetteFile->move(
                    self::SERVER_PATH_TO_IMG_FOLDER,
                    $uniqueName
                );
            }
            $this->vignette = $uniqueName;
            if ($this->vignetteFile instanceof UploadedFile) {
                
            }
            }else{
                $this->vignetteFile = $vignetteFile;
            }
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     *
     * @param bool $mustKeepOriginal
     * @throws Exception
     */
    public function uploadVignetteImg($mustKeepOriginal = false)
    {
        // the VignetteFile property can be empty if the field is not required
        if (null === $this->getVignetteFile()) {
            return;
        }
        //verifie si il existe déjà un fichier si oui on le supprime
        if($this->getVignetteName() !== null){
            array_map('unlink', glob(getcwd().'/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getId()."/*"));
        }

        // we use the original imagefile name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and target filename as params
        if($mustKeepOriginal){
            $dir = getcwd().'/public/'.self::SERVER_PATH_TO_IMG_FOLDER;
            if (!is_dir($dir)) {
                if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                    throw new FileException(sprintf('Unable to create the "%s" directory', $dir));
                }
            } elseif (!is_writable($dir)) {
                throw new FileException(sprintf('Unable to write in the "%s" directory', $dir));
            }
            copy($this->getVignetteFile()->getRealPath(), getcwd().'/public/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getVignetteFile()->getClientOriginalName());
        } else {
            $this->getVignetteFile()->move(
                getcwd() . '/' . self::SERVER_PATH_TO_IMG_FOLDER,
                $this->getVignetteFile()->getClientOriginalName()
            );
        }

        // set the path property to the filename where you've saved the imageFile
        $this->setVignette($this->getVignetteFile()->getClientOriginalName());

        // clean up the file property as you won't need it anymore
        $this->setVignetteFile(null, false);
    }

    /**
     * Retourne le chemin de la video
     */
    public function getWebPathImg()
    {
        return '/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getVignette();
    }

    /**
     * Génère un nom aléatoire
     * @param File $file
     * @return string
     */
    public function generateUniqueName(File $file): string
    {
        if ($file) {
            return md5( uniqid() ) . "." . $file->guessExtension();
        } else {
            return null;
        }
    }

    public function getChapitre(): ?Chapitre
    {
        return $this->chapitre;
    }

    public function setChapitre(?Chapitre $chapitre): self
    {
        $this->chapitre = $chapitre;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getOuvrage(): ?Ouvrage
    {
        return $this->ouvrage;
    }

    public function setOuvrage(?Ouvrage $ouvrage): self
    {
        $this->ouvrage = $ouvrage;

        return $this;
    }

}
