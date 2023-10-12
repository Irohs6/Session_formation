<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Category;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProgrammeRepository;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {

        $sessions = $sessionRepository->findAll();

        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('liste_stagiaires', name: 'app_listeStagiaire')]
    public function listeStagiaire(StagiaireRepository $stagiaireRepository): Response
    {

        $stagiaires = $stagiaireRepository->findAll();

        return $this->render('session/listeStagiaire.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    #[Route('liste_modules', name: 'app_listeModule')]
    public function listeModule(ModuleRepository $moduleRepository): Response
    {

        $modules = $moduleRepository->findAll();

        return $this->render('session/listeModules.html.twig', [
            'modules' => $modules,
        ]);
    }

    #[Route('liste_categories', name: 'app_listecategories')]
    public function listecategorie(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();

        return $this->render('session/listecategories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/session/detail{id}', name: 'app_detailProgramme')]
    public function detailProgramme(Session $session): Response
    {

        return $this->render('session/detailProgramme.html.twig', [
            'session' => $session,
        ]);
    }    

    #[Route('/session/listeSession{id}', name: 'app_listeSessionParFormation')]
    public function listeSessionParFormation(Formation $formation): Response
    {

        return $this->render('session/listeSessionParFormation.html.twig', [
            'formation' => $formation,
        ]);
    }    

    #[Route('detailStagiaire{id}', name: 'app_detailStagiaire')]
    public function detailStagiaire(Stagiaire $stagiaire): Response
    {

        return $this->render('session/detailStagiaire.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }    



}
