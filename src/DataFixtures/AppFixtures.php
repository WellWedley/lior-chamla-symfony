<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }





    public function load(ObjectManager $manager): void
    {


        /**
         * Call to pixabay's api
         */
        $apiKey = "31957690-47f13ba916d25ec07f9e84f72";
        $apiUrl = "https://pixabay.com/api/?key=$apiKey&q=product+shop&image_type=photo";




        $cat_tab = ['Alimentation & epicerie', 'Beauté & hygiène', 'Sport & loisirs'];

        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $cat_name = $cat_tab[rand(0, 2)];
            $category->setName($cat_name)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));
            $manager->persist($category);


            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;

                /**
                 * Response from pixabay
                 */
                $response = file_get_contents($apiUrl, False);
                $data = json_decode($response, true);

                $manager->persist($category);
                $product->setName("Produit n p $p")
                    ->setPrice(mt_rand(1030, 26300))
                    ->setSlug(strToLower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setMainPicture($data['hits'][rand(0, 15)]['largeImageURL'])

                    ->setShortDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam dignissimos placeat recusandae sit eius dolores tempore, commodi sunt a laborum laboriosam, accusantium non mollitia? Alias aspernatur quae quam rerum optio!
                    ");

                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
