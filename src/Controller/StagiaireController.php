<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Form\SessionAddType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'StagiaireController',
        ]);
    }

     //Function pour afficher la liste des stagiaires****************************************************
    //chemin d'accès de la page https://127.0.0.1:8000/liste_stagiaires
    #[Route('liste_stagiaires', name: 'app_listeStagiaire')]
    public function listeStagiaire(StagiaireRepository $stagiaireRepository): Response
    {
        $stagiaires = $stagiaireRepository->findAll(); //recupère toute les données de la table stagiaire
        //renvoie le résultat vers le template session/listeStagiaire
        return $this->render('stagiaire/listeStagiaire.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

     //Function pour afficher le détail d'un stagiaire par son id ***************************************/
     #[Route('detailStagiaire{id}', name: 'app_detailStagiaire')]
     public function detailStagiaire(Stagiaire $stagiaire): Response
     {
         //renvoie le détail d'un Stagiaire vers le template detailStagiaire
         return $this->render('stagiaire/detailStagiaire.html.twig', [
             'stagiaire' => $stagiaire,
         ]);
     }  

     /*******************Ajout et Modification d'un stagiaire****************************************************************** */
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
        return $this->render('stagiaire/new_stagiaire.html.twig', [
            'formStagiaire' => $form,// pour la création du formulaire
            'edit' => $stagiaire->getId(),//envoit l'id de stafiaire pour confirmer si modifier ou ajouter          
        ]);
    }

      /****************************Ajout de session a 1 stagiaire ne marche pas************************************************************* */
      #[Route('addSession/{id}', name: 'app_addSession')]
      public function addSession(Stagiaire $stagiaire, Request $request, EntityManagerInterface $entityManager)
      { 
          $formAddSession = $this->createForm(SessionAddType::class, $stagiaire);//creer le formulaire
          //recupère les données du formulaire si il a été soumis et validé
          $formAddSession->handleRequest($request);
          //si le formulaire est soumit et valide 
          
          if ($formAddSession->isSubmitted() && $formAddSession->isValid()) {
              //récupère les donné du formulaire 
              $data = $formAddSession->getData();
              
             // Les sessions sélectionnées sont maintenant dans $data->getSessions()
              // Vous pouvez les ajouter au stagiaire
              foreach ($data->getSessions() as $session) {
                  $stagiaire->addSession($session);
              }
              // prepare PDO la requete insert ou update
              $entityManager->persist($stagiaire);
              // execute PDO la requete insert ou update
              $entityManager->flush();
              $this->addFlash('success', 'Sessions ajoutées avec succès.');
              
             return $this->redirectToRoute('app_detailStagiaire', ['id'=> $stagiaire->getId()]);
      
          }
          // sinon return sur le formulaire pour corriger les ereur
          return $this->render('stagiaire/add_session.html.twig', [
              'formAddSession' => $formAddSession->createView(),
              'stagiaire' => $stagiaire,
          ]);
      }


}