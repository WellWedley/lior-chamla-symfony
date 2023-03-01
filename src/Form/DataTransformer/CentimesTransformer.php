<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;



class CentimesTransformer implements DataTransformerInterface
{
    public function transform(mixed $value)
    {
        //si la valeur est nulle, c'est qu'on est sur un formulaire de création
        // Alors on ne fait rien
        if (
            $value
            === null
        ) {
            return;
        }

        // Dans l'autre cas (edition du produit), on affiche la valeur /100
        return $value / 100;
    }


    public function reverseTransform(mixed $value)
    {

        if (
            $value
            === null
        ) {
            return;
        }
        // on enregistre de nouveau la valeur dans la BDD en la multipliant par 100
        return $value * 100;
    }
}
