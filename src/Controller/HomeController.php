<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Guesser\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage(EntityManagerInterface $emi, ProductRepository $productRepository)
    {

        $products = $productRepository->findBy([], [], 20);

        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
}
