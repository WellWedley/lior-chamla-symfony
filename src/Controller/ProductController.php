<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ProductController extends AbstractController
{


    /**
     * @Route("/{slug}", name="product_category")
     */
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);



        if (!$category) {
            throw new NotFoundHttpException("La catégorie n'existe pas !");
        }

        return $this->render('product/category.html.twig', [

            'slug' => $slug,
            'category' => $category

        ]);
    }



    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     */
    public function show(
        $slug,
        ProductRepository $productRepository,
        UrlGeneratorInterface $urlGenerator
    ) {

        $product = $productRepository->findOneBy([
            'slug' => $slug,

        ]);



        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas !");
        }


        return $this->render('/product/show.html.twig', [
            'product' => $product,
            'urlGenerator' => $urlGenerator


        ]);
    }



    /**
     * @Route("admin/product/{id}/edit", name="product_edit")
     */

    public function edit(
        $id,
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        SluggerInterface $slugger

    ) {

        //Cherche le produit à modifier selon l'ID
        $product = $productRepository->find($id);

        $form = $this->createForm(
            ProductType::class,
            $product,

        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->flush();



            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }


        $formView = $form->createView();

        return $this->render(
            'product/edit.html.twig',
            [
                'product' => $product,
                'formView' => $formView
            ]
        );
    }


    /**
     * @Route("admin/product/create", name="product_create")
     */
    public function create(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager
    ) {


        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        };


        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}
