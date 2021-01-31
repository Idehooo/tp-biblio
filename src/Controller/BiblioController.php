<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CollectionOuvrageRepository;
use App\Repository\OuvrageRepository;
use App\Repository\ChapitreRepository;
use App\Repository\SectionRepository;
use App\Entity\Favori;

class BiblioController extends AbstractController
{
    /**
     * @Route("/biblio", name="biblio")
     */
    public function biblio(CollectionOuvrageRepository $collectionOuvrage): Response
    {
        $user = $this->getUser();
        $collections = $collectionOuvrage->findAll();

        return $this->render('biblio/main.html.twig', [
            'user' => $user,
            'collections' => $collections
        ]);
    }

    /**
     * @Route("/biblio/fav", name="biblioFav")
     */
    public function biblioFav(): Response
    {
        $user = $this->getUser();

        return $this->render('biblio/fav.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/biblio/book/{id}", name="biblioBook")
     */
    public function bibliobook(OuvrageRepository $ouvrageRepository, $id): Response
    {
        $user = $this->getUser();
        $book = $ouvrageRepository->find($id);

        return $this->render('biblio/book.html.twig', [
            'user' => $user,
            'book' => $book
        ]);
    }

    /**
     * @Route("/biblio/addFav/ouvrage/{id}", name="biblioAddFavOuvrage")
     */
    public function biblioAddFavOuvrage(OuvrageRepository $ouvrageRepository, $id): Response
    {
        $user = $this->getUser();
        $book = $ouvrageRepository->find($id);
        if (!$user || !$book) {
            return("error");
        } else {
            $favori = new Favori();
            $favori->setUser($user);
            $favori->setOuvrage($book);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favori);
        $entityManager->flush();

        return $this->redirectToRoute('biblio');
    }

    /**
     * @Route("/biblio/addFav/chapitre/{id}", name="biblioAddFavChapitre")
     */
    public function biblioAddFavChapitre(ChapitreRepository $chapitreRepository, $id): Response
    {
        $user = $this->getUser();
        $chapter = $chapitreRepository->find($id);
        if (!$user || !$chapter) {
            throw('error');
        } else {
            $favori = new Favori();
            $favori->setUser($user);
            $favori->setChapitre($chapter);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favori);
        $entityManager->flush();

        return $this->redirectToRoute('biblio');
    }

    /**
     * @Route("/biblio/addFav/section/{id}", name="biblioAddFavSection")
     */
    public function biblioAddFavSection(SectionRepository $sectionRepository, $id): Response
    {
        $user = $this->getUser();
        $section = $sectionRepository->find($id);
        if (!$user || !$section) {
            throw('error');
        } else {
            $favori = new Favori();
            $favori->setUser($user);
            $favori->setSection($section);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favori);
        $entityManager->flush();

        return $this->redirectToRoute('biblio');
    }
}
