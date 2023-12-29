<?php

namespace App\Form;

use App\Entity\Auteur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // attention il peu choisir le textype au mauvais endroit
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuteurTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('nom',TextType::class,[
                "required" => false,
                "constraints" => [
                    new NotBlank([ "message" => "Le nom ne peut pas étre vide" ]),
                    new Length([
                        "max" => 30,
                        "maxMessage" => "Le nom ne doit pas depasser 30 caractères",
                        "min" => 2,
                        "minMessage" => "Le nom doit comporter 2 caractères minimum"
                    ])
                ]
                ])
            ->add('biographie')
            ->add('naissance')
            ->add('deces')
            ->add('enregistrer',SubmitType::class,["attr" => ["class" => "btn btn-success"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
