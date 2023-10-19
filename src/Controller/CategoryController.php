<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Category;
use App\Form\ModuleType;
use App\Form\CategoryType;
use App\Repository\ModuleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }


        //Function pour afficher la liste des modules************************************************************
    //Chemin d'accèes a la pages https://127.0.0.1:8000/liste_modules
    #[Route('category/{id}/listModule', name: 'app_listeModule')]
    public function listeModule(Category $category): Response
    {
        $modules = $category->getModules();

        //renvoie le resultat vers le template session/listeModule
        return $this->render('category/listeModules.html.twig', [
            'modules' => $modules,
            'category' => $category,
        ]);
    }

    //Function qui Affiche la liste des categories**********************************************************
    //chemin d'accès a la pages https://127.0.0.1:8000/liste_categories   
    #[Route('liste_categories', name: 'app_listecategories')]
    public function listecategorie(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();//recupère toute les données de la table catégory
        //renvoie le resultat vers le template session/listecategories
        return $this->render('category/listecategories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete_category')]
    public function deleteCategory(Category $category, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Category supprimé!'
        );

        return $this->redirectToRoute('app_listeStagiaire');
    }
    /*********Ajout ou edit d'une catégory**************************************************************************** */
    //chemin pour une créations
    #[Route('category/new', name: 'new_category')]
    //chemion pour une modification
    #[Route('category/{id}/edit', name: 'edit_category')]
    public function newCategory(Category $category = null, Request $request, EntityManagerInterface $entityManager): Response
    {   
        //si catégories n'exsite pas créer une nouvelle instance
        if(!$category){
            $category = new Category();
        }
        
        $form = $this->createForm(CategoryType::class, $category);//crée le formaulaire
        //recupère les données du formulaire si il a été soumis et les valide
        $form->handleRequest($request);

        //si le formulaire est soumit et que les données son valide
        if ($form->isSubmitted() && $form->isValid()) {
            //recupère les donnée du formulaire
            $category = $form->getData();
            // prepare la requete insert ou update
            $entityManager->persist($category);
            // execute la requete insert ou update
            $entityManager->flush();
            //redirige vers la liste des catégories si tous c'est bien passé
            return $this->redirectToRoute('app_listecategories');
        }
        //sinon return vers le formulaire pour corriger les erreur
        return $this->render('category/new_category.html.twig', [
            'formCategory' => $form,
            'edit' => $category->getId(),
        ]);
    }
/**Ajout ou Modifier un Modules************************************************************ */
    #[Route('module/new/{idCategory}', name: 'new_module')]
    #[Route('module/{id}/edit', name: 'edit_module')]
    public function newModule(Module $module = null, Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        //si module n'éxiste pas crer une nouvelle instance
        if(!$module){
            $module = new Module();
            $idCategory = $request->attributes->get('idCategory'); //on recupère l'id de la formation contenu dans l'url
            $category = $categoryRepository->findOneBy(['id'=> $idCategory]);//on récupère la category grace a cet id
        }else{
            $category = $module->getCategory();// si la sessio existe déja on recupère sa CatgeorgetCategory
        }
    
        $module->setCategory($category); // on inclu la session existante ou la nouvelle dans sa formation

        $form = $this->createForm(ModuleType::class, $module);//creer le formulaire

        //recupère les données du formulaire si il a été soumis et validé
        $form->handleRequest($request);

        //si formulaire soumit et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //récupère les donnée du formulaire
            $module = $form->getData();
            // prepare PDO la requete update ou insert
            $entityManager->persist($module);
            // execute PDO la requete update ou insert
            $entityManager->flush();
            //retrun a liste module si tous c'est bien passée
            return $this->redirectToRoute('app_listeModule',['id'=>$category->getId()]);
        }
        //sinon return sur le formulaire
        return $this->render('category/new_Module.html.twig', [
            'formModule' => $form,
            'edit' => $module->getId(),
        ]);
    }

    #[Route('/{id}/deleteModule', name: 'delete_module')]
    public function deleteModule(Module $module, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($module);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Module supprimé!'
        );

        return $this->redirectToRoute('app_listeModule',['id'=>$module->getCategory()->getId()]);
    }
   
}


