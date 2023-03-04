<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', Assert\TextType::class, [
                'label' => 'Nom du produit',
                //Les attributs (classe, placeholder) correspondent aux attributs html que l'on peut appliquer à un formulaire 
                // C'est un tableau car il peut recevoir plusieurs attributs 
                'attr' => [
                    'placeholder' => 'Tapez le nom du produit',
                ],
                'required' => False
            ])

            ->add('shortDescription', Assert\TextareaType::class, [
                'label' => 'Description courte',
                'attr' => [
                    'placeholder' => 'Tapez une description courte et parlante pour le visiteur',
                ]
            ])

            ->add('price', Assert\MoneyType::class, [
                'attr' => [
                    'placeholder' => 'Tapez le prix du produit en euros.',
                ],
                'divisor' => 100,
                'required' => False
            ])

            ->add('category', EntityType::class, [ //Ici, on indique que le champs sera tiré d'une entité de la BDD
                'label' => 'Catégorie',

                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,/*Ici on indique quelle classe est censée s'afficher pour ce Champs*/
                'choice_label' => 'name'
            ])

            ->add('mainPicture', Assert\UrlType::class, [
                'label' => 'Image du produit',
                'attr' => ['placeholder' => 'Tapez une Url d\'image'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
