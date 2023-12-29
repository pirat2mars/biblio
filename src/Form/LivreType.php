<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('resume')
            ->add('couverture', FileType::class,[
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new File([
                        "mimeTypes"         => ["image/jpeg", "image/png" ,"image/gif"],
                        "mimeTypesMessage" => "Les formats autorisés sont : jpg,png et gif",
                        "maxSize"           => "2048k",
                        "maxSizeMessage" => "Le fichier ne doit pas peser plus de 2Mo"
                    ])

                ],
                "help" => "Formats autorisés : images ( jpeg, png , gif )"

            ] )
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                /* dans l'option label on peut:
                    -choisir la propriete qui sera utilisé dans l'affichage du select
                    - écrire une fonction qui retourne ce qui doit etre affiché
                */
                'choice_label' => function(Auteur $auteur){
                    //return $auteur->getId() . " - " .$auteur->getPrenom() . " " . $auteur->getNom(); 
                    return $auteur->getIdentite();
                },
                'placeholder' => 'Sélectionner un auteur'
            ])
            ->add('genres',EntityType::class,[
                'class' => Genre::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true, // liste de case a cocher
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
