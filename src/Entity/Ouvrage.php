<?php

namespace App\Entity;

use App\Repository\OuvrageRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity
 * @ORM\Table(name="ouvrage")
 * 
 * @Vich\Uploadable
 */
class Ouvrage
{
    const SERVER_PATH_TO_IMG_FOLDER = 'medias/images';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couverture;

    /**
     * @Vich\UploadableField(mapping="couvertureFile", fileNameProperty="couverture")
     * @var File
     */
    private $couvertureFile;

    /**
     * @ORM\ManyToOne(targetEntity=CollectionOuvrage::class, inversedBy="ouvrages")
     */
    private $collectionOuvrage;

    /**
     * @ORM\OneToMany(targetEntity=Chapitre::class, mappedBy="ouvrage", orphanRemoval=true)
     */
    private $chapitres;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="ouvrage")
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity=Favori::class, mappedBy="ouvrage")
     */
    private $favoris;

    public function __construct()
    {
        $this->chapitres = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->titre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return string
     */
    public function getCouverture(): ?string
    {
        return $this->couverture;
    }

    /**
     * @return string $couverture
     */
    public function setCouverture(?string $couverture): self
    {
        $this->couverture = $couverture;

        return $this;
    }


    /**
     * @return UploadedFile
     */
    public function getCouvertureFile()
    {
        return $this->couvertureFile;
    }

    /**
     * @param UploadedFile $vignetteFile
     * @param bool $delete
     * @throws Exception
     */
    public function setCouvertureFile($couvertureFile, $delete = true)
    {
        if ($delete) {
            $uniqueName = $this->generateUniqueName( $couvertureFile );
            if ($uniqueName != null) {
                $couvertureFile->move(
                    self::SERVER_PATH_TO_IMG_FOLDER,
                    $uniqueName
                );
            }
            $this->couverture = $uniqueName;
            if ($this->couvertureFile instanceof UploadedFile) {
                
            }
            }else{
                $this->couvertureFile = $couvertureFile;
            }
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     *
     * @param bool $mustKeepOriginal
     * @throws Exception
     */
    public function uploadCouvertureImg($mustKeepOriginal = false)
    {
        // the VignetteFile property can be empty if the field is not required
        if (null === $this->getCouvertureFile()) {
            return;
        }
        //verifie si il existe déjà un fichier si oui on le supprime
        if($this->getCouvertureName() !== null){
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
            copy($this->getCouvertureFile()->getRealPath(), getcwd().'/public/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getCouvertureFile()->getClientOriginalName());
        } else {
            $this->getCouvertureFile()->move(
                getcwd() . '/' . self::SERVER_PATH_TO_IMG_FOLDER,
                $this->getCouvertureFile()->getClientOriginalName()
            );
        }

        // set the path property to the filename where you've saved the imageFile
        $this->setCouverture($this->getCouvertureFile()->getClientOriginalName());

        // clean up the file property as you won't need it anymore
        $this->setCouvertureFile(null, false);
    }

    /**
     * Retourne le chemin de la video
     */
    public function getWebPathImg()
    {
        return '/'.self::SERVER_PATH_TO_IMG_FOLDER.'/'.$this->getCouverture();
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

    public function getCollectionOuvrage(): ?CollectionOuvrage
    {
        return $this->collectionOuvrage;
    }

    public function setCollectionOuvrage(?CollectionOuvrage $collectionOuvrage): self
    {
        $this->collectionOuvrage = $collectionOuvrage;

        return $this;
    }

    /**
     * @return Collection|Chapitre[]
     */
    public function getChapitres(): Collection
    {
        return $this->chapitres;
    }

    public function addChapitre(Chapitre $chapitre): self
    {
        if (!$this->chapitres->contains($chapitre)) {
            $this->chapitres[] = $chapitre;
            $chapitre->setOuvrage($this);
        }

        return $this;
    }

    public function removeChapitre(Chapitre $chapitre): self
    {
        if ($this->chapitres->removeElement($chapitre)) {
            // set the owning side to null (unless already changed)
            if ($chapitre->getOuvrage() === $this) {
                $chapitre->setOuvrage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setOuvrage($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getOuvrage() === $this) {
                $ressource->setOuvrage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Favori[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favori $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setOuvrage($this);
        }

        return $this;
    }

    public function removeFavori(Favori $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getOuvrage() === $this) {
                $favori->setOuvrage(null);
            }
        }

        return $this;
    }


}
