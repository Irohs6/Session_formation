<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //chemin d'accès de la pages https://127.0.0.1:8000/home
    #[Route('/home', name: 'app_home')]
    public function index(FormationRepository $formationRepository): Response
    {
        //pour récupérer les donnée de la table formation
        $formations = $formationRepository->findAll();
        //return les résultat sur le template index
        return $this->render('home/index.html.twig', [
            'formations' => $formations,
        ]);
    }
}
