<?php

namespace App\Controller;


use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\FormationType;
use App\Form\ProgrammeType;
use App\Form\StagiaireType;
use App\Form\ProgrammeSessionType;
use App\Form\SessionStagiaireType;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    //Function pour afficher la liste des sessions********************************************************
    //Chemin d'accès de la pages https://127.0.0.1:8000/session
    #[Route('session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();//récupère les toutes les données de la table session
        //return le résultat sur le template  session/index
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    //Function pour afficher la liste des stagiaires****************************************************
    //chemin d'accès de la page https://127.0.0.1:8000/liste_stagiaires
    #[Route('liste_stagiaires', name: 'app_listeStagiaire')]
    public function listeStagiaire(StagiaireRepository $stagiaireRepository): Response
    {
        $stagiaires = $stagiaireRepository->findAll(); //recupère toute les données de la table stagiaire
        //renvoie le résultat vers le template session/listeStagiaire
        return $this->render('session/listeStagiaire.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

//*Pour afficher le programme d'une session******************************************************************** */
//chemin d'acès a la page  https://127.0.0.1:8000/session/detail1  
    #[Route('/session/detail{id}', name: 'app_detailProgramme')]
    //on utilise Session pour récupérer son id
    public function detailProgramme(Session $session): Response
    {
        //renvoie le resultat de la session sélectionné par son id vers le template detailProgramme
        return $this->render('session/detailProgramme.html.twig', [
            'session' => $session,
        ]);
    }    


    //Pour afficher la liste des session trier par formation(dev, bureautique,.....)
    #[Route('/session/listeSession{id}', name: 'app_listeSessionParFormation')]
    public function listeSessionParFormation(Formation $formation): Response
    {
        //renvoie le liste des session contenu dans une formation par son identifiant 
        return $this->render('session/listeSessionParFormation.html.twig', [
            'formation' => $formation,
        ]);
    }    

    //Function pour afficher le détail d'un stagiaire par son id ***************************************/
    #[Route('detailStagiaire{id}', name: 'app_detailStagiaire')]
    public function detailStagiaire(Stagiaire $stagiaire): Response
    {
        //renvoie le détail d'un Stagiaire vers le template detailStagiaire
        return $this->render('session/detailStagiaire.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }  

    //Function pour ajouter et editer une nouvelle formation*******************************************************
    #[Route('formation/new', name: 'new_formation')]
    #[Route('formation/{id}/edit', name: 'edit_formation')]
    public function newFormation(Formation $formation = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si formation n'existe pas on créer une nouvelle instance pour l'ajout
        if(!$formation){
            $formation = new Formation();
        }

        //creer le formulaire
        $form = $this->createForm(FormationType::class, $formation);
        //recupère les données du formulaire si il a été soumis et validé
        $form->handleRequest($request);
        //si le formulaire est remplie et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //récupère les donné du formulaire  
            $formation = $form->getData();
            // prepare PDO(prepare la requete Insert ou Update)
            $entityManager->persist($formation);
            // execute PDO(la requete Insert ou Update)
            $entityManager->flush();
            //redirige ver le home qui est la liste des formation
            return $this->redirectToRoute('app_home');
        }
        //return la vue du formullaire en cas d'erreur
        return $this->render('session/new_formation.html.twig', [
            'form' => $form,//pour la creation du formulaire 
            'edit' => $formation->getId()//renvoie edit vers le template du formulaire pour pouvoir définir si edit ou add
        ]);
    }

/******Ajout et Modification d'une session********************** */
    #[Route('session/new', name: 'new_session')]
    #[Route('session/{id}/edit', name: 'edit_session')]
    public function newsession(Session $session =null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si session n,'existe pas creer une nouvelle instance
        if(!$session){
            $session = new Session();
        }

        // $programmeSession = new Programme();
        $form = $this->createForm(SessionType::class, $session);//creer le formulaire
       
        //recupère les données du formulaire si il a été soumis et validé
        $form->handleRequest($request);
        //si le formulaire est soumit et valide 
        if ($form->isSubmitted() && $form->isValid()) {
            //récupère les donné du formulaire 
            $session = $form->getData();
            // prepare PDO la requete insert ou update
             $entityManager->persist($session);
            // execute PDO la requete insert ou update
            $entityManager->flush();
            
           return $this->redirectToRoute('app_detailProgramme', ['id'=> $session->getId()]);
    
        }
        // sinon return sur le formulaire pour corriger les ereur
        return $this->render('session/new_session.html.twig', [
            'formSession' => $form,
            'edit' => $session->getId(),
        ]);
    }
/*******************Ajout et Modification d'un stagiaire******************************************* */
    #[Route('stagiaire/new', name: 'new_stagiaire')]
    #[Route('stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function newStagiaire(Stagiaire $stagiaire = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si stagiaire n'est pas vrais creer une nouvelle instance
        if(!$stagiaire){
            $stagiaire = new Stagiaire();
        }
       
        $form = $this->createForm(StagiaireType::class, $stagiaire);//créer le formulaire 
        //recupère les données du formulaire si il a été soumis et les valide
        $form->handleRequest($request);

        //si le formulaire est soumit et qu'il est valide 
        if ($form->isSubmitted() && $form->isValid()) {
             //recdupère les données 
            $stagiaire = $form->getData();
            // prepare la requete avec les données du formaulaire
            $entityManager->persist($stagiaire);
            // execute PDO la requete
            $entityManager->flush();
            //redirige vers la liste des stagiaire
            return $this->redirectToRoute('app_detailStagiaire',['id'=>$stagiaire->getId()]);
        }
        //sinon return sur le formulaire pour corriger les ereur
        return $this->render('session/new_stagiaire.html.twig', [
            'formStagiaire' => $form,// pour la création du formulaire
            'edit' => $stagiaire->getId(),//envoit l'id de stafiaire pour confirmer si modifier ou ajouter          
        ]);
    }

    //pour modifier ou ajouter un nouveau programme
    #[Route('programme/new', name: 'new_programme')]
    #[Route('Programme/{id}/edit', name: 'edit_programme')]
    public function newProgramme(Programme $programme = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //si programme n'existe pas on crer une nouvelle instance
        if(!$programme){
            $programme = new Programme();
        }

        $form = $this->createForm(ProgrammeType::class, $programme);//creer le formulaire
        //recupère les données du formulaire si il a été soumis et validé
        $form->handleRequest($request);

        //si le formulaire est soumit et qu'il est valide 
        if ($form->isSubmitted() && $form->isValid()) {
            $id=$programme->getId();
             //recdupère les données 
            $programme = $form->getData();
            // prepare la requete avec les données du formaulaire
            $entityManager->persist($programme);
            // execute PDO la requete
            $entityManager->flush();
            //redirige  ver la liste des session
            return $this->redirectToRoute('app_detailProgramme',['id' => $programme->getSession()->getId()]);
        }
        //return au formulaire pour corriger les erreur
        return $this->render('session/new_programme.html.twig', [
            'formProgramme' => $form,
            'edit' => $programme->getId(),
        ]);
    }

}
