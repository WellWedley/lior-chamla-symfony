<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\DataTransformer\CentimesTransformer;
use App\Form\Type\PriceType\PriceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                //Les attributs (classe, placeholder) correspondent aux attributs html que l'on peut appliquer à un formulaire 
                // C'est un tableau car il peut recevoir plusieurs attributs 
                'attr' => [
                    'placeholder' => 'Tapez le nom du produit'
                ]
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => [
                    'placeholder' => 'Tapez une description courte et parlante pour le visiteur',
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'placeholder' => 'Tapez le prix du produit en euros.',
                ],
                'divisor' => 100

            ])


            ->add('category', EntityType::class, [ //Ici, on indique que le champs sera tiré d'une entité de la BDD
                'label' => 'Catégorie',

                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,/*Ici on indique quelle classe est censée s'afficher pour ce Champs*/
                'choice_label' => 'name'


            ])



            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr' => ['placeholder' => 'Tapez une Url d\'image'],
            ]);





        // $builder
        //     ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //         $form = $event->getForm();

        //         /**
        //          * @var Product
        //          */
        //         $product = $event->getData();


        //         if ($product->getPrice() == !null) {
        //             $product->setPrice($product->getPrice() / 100);
        //         }
        //     });


        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     $product = $event->getData();

        //     if ($product->getPrice() == !null) {

        //         $product->setPrice($product->getPrice() * 100);
        //     }
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
