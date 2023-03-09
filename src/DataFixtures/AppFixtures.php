<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
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



        $admin = new User;
        $admin->setEmail("admin@gmail.com")

            //Fonction utilisée dans le tutpriel n'existe, nouvelle fonction non compatible avec la fonction load
            //-> Utilisatiion de la fonction native passord_hash()
            ->setPassword(password_hash("password", PASSWORD_DEFAULT))
            ->setFullName("Admin")
            ->setRoles(["ROLE_ADMIN"]);

        $manager->persist($admin);

        $firstNames = ["Pierre", "Paul", "Jack", "Or", "nicar"];
        $lastNames = ["Dupond", "Lamartine", "Ritchie", "Martin", "Duboucher"];

        for ($u = 0; $u < 5; $u++) {

            $user = new User();
            $user
                ->setEmail("user" . $u . "@gmail.com")
                ->setFullName($firstNames[$u] . " " . $lastNames[$u])
                ->setPassword(password_hash("password", PASSWORD_DEFAULT));

            $manager->persist($user);
        }



        /**
         * Call to pixabay's api
         */
        $apiKey = "31957690-47f13ba916d25ec07f9e84f72";
        $apiUrl = "https://pixabay.com/api/?key=$apiKey&q=smartphone&image_type=photo";



        //Auto generate categories 
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

                $manager
                    ->persist($category);

                $product
                    ->setName("Produit n p $p")
                    ->setPrice(mt_rand(1030, 26300))
                    ->setSlug(strToLower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setMainPicture($data['hits'][$p]['webformatURL'])
                    ->setShortDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit.");

                $manager
                    ->persist($product);
            }
        }
        $manager
            ->flush();
    }
}
