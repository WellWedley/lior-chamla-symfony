<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\Type\PriceType;

class CategoryController extends AbstractController
{


    protected $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository)
    {

        $this->categoryRepository = $categoryRepository;
    }


    public function renderMenuList()
    {
        //Aller chercher les catégories dans la BDD ( CategoryRepository)

        $categories = $this->categoryRepository->findAll();


        //Renvoyer les résultats sous forme d'une response 
        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/admin/category/create", name ="category_create")
     */
    public function createCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ) {

        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setSlug(strtolower($slugger->slug($category->getName())));

            $entityManager->persist($category);
            $entityManager->flush();


            //Si le formulaire est soumis et la nouvelle catégori enregistrée
            // Reirection vers la page d'accueil
            return $this->redirectToRoute('product_category', [
                'category_slug' => $category->getSlug()
            ]);
        }


        $formView = $form->createView();

        return $this->render('category/create.html.twig', [
            'formView' => $formView
        ]);
    }


    /**
     * @Route("admin/category/{id}/edit", name="category_edit")
     */
    public function editCategory(
        $id,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        Request $request
    ) {

        //Looking form the form to edit, according to a given id
        $category = $categoryRepository->find($id);

        //Creating the form from CategoryType's class form 
        $form = $this->createForm(CategoryType::class, $category);


        //handles request, checks if the form is submitted, saves
        //then redirects to homepage's location

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $entityManager->flush();


            return $this->redirectToRoute('product_category', [
                'slug' => $category->getSlug(),

            ]);
        }

        //Creating form's view 
        $formView = $form->createView();

        //renders the form
        return $this->render(
            'category/edit.html.twig',
            [
                'category' => $category,
                'formView' => $formView
            ]
        );
    }
}
