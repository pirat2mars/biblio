<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // pour la verification du mot de passe
        $abonne = $options["data"];

        $builder
            ->add('pseudo')
            ->add('roles',ChoiceType::class,[
                "choices" => [
                                "Administrateur"    => "ROLE_ADMIN",
                                "Bibliothécaire"    => "ROLE_BIBLIO",
                                "Lecteurs"          => "ROLE_LECTEUR",
                                "Abonné"            => "ROLE_USER",
                                "Développeur"       => "ROLE_DEV"
                ],
                "multiple" => true,
                "expanded" => true,
                "label" => "Droit d'accès"

            ])
            ->add('password', TextType::class,[
                "mapped" => false,
                "required" => $abonne->getId() ? false : true,
            ])
            ->add('prenom')
            ->add('nom')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
