<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\AddStagiaire;
use App\Form\FormationType;
use App\Form\ProgrammeType;
use App\Form\ModuleSessionType;
use App\Form\AddModulesSessionType;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
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
        $sessions = $sessionRepository->findBy([],["DateDebut" => "ASC"]);//récupère les toutes les données de la table session
        //return le résultat sur le template  session/index
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

   
//*Pour afficher le programme d'une session******************************************************************** */
//chemin d'acès a la page  https://127.0.0.1:8000/session/detail1  
    #[Route('/session/detail{id}', name: 'app_detailSession')]
    //on utilise Session pour récupérer son id
    public function detailSession(Session $session): Response
    {
    
        //renvoie le resultat de la session sélectionné par son id vers le template detailSession
        return $this->render('session/detailSession.html.twig', [
            'session' => $session,
        ]);
    }    


    //Pour afficher la liste des session trier par formation(dev, bureautique,.....)
    #[Route('/session/listeSession{id}', name: 'app_listeSessionParFormation')]
    public function listeSessionParFormation(Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $sessions = $entityManager->getRepository(Session::class)->findBy(
            ['formation' => $formation],
            ['dateDebut' => 'ASC'] // Tri par date de début en ordre croissant
        );
        
        //renvoie le liste des session contenu dans une formation par son identifiant 
        return $this->render('session/listeSessionParFormation.html.twig', [
            'formation' => $formation,
            'sessions' => $sessions,
            
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
            $this->addFlash(
                'notice',
                'Session ajouté avec succès!'
            );
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
            'edit' => $formation->getId(),//renvoie edit vers le template du formulaire pour pouvoir définir si edit ou add
            'formation' => $formation,
        ]);
    }
   

    #[Route('formation/{id}/delete', name: 'delete_formation')]
    public function deleteFormation(Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Formation supprimé!'
        );

        return $this->redirectToRoute('app_home');
    }

    

/******Ajout et Modification d'une session*********************************************** */
    #[Route('session/{idFormation}/new', name: 'new_session')]
    #[Route('session/{id}/edit', name: 'edit_session')]
    public function newSession(Session $session = null, Request $request, EntityManagerInterface $entityManager, FormationRepository $formationRepository): Response
    {   
        
        if(!$session){
            //si session n'existe pas creer une nouvelle instance
            $session = new Session();  
            $idFormation = $request->attributes->get('idFormation'); //on recupère l'id de la formation contenu dans l'url
            $formation = $formationRepository->findOneBy(['id'=> $idFormation]);//on récupère la formation grace a cet id
        }else{
            $formation = $session->getFormation();// si la sessio existe déja on recupère sa formation
        }

        $session->setFormation($formation); // on inclu la session existante ou la nouvelle dans sa formation
       
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
            
           return $this->redirectToRoute('app_detailSession', ['id'=> $session->getId()]);
    
        }
        // sinon return sur le formulaire pour corriger les ereur
        return $this->render('session/new_session.html.twig', [
            'form' => $form,
            'edit' => $session->getId(),
            'sessionId' => $session->getId(),
            'session' => $session,
        ]);
    }  


    #[Route('session/{id}/delete_session', name: 'delete_session')]
    public function deleteSession(Session $session, EntityManagerInterface $entityManager): Response
    {   
        
        $entityManager->remove($session);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Session supprimé!'
        );

        return $this->redirectToRoute('app_home');
      
          
    }  

}
