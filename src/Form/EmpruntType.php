<?php

namespace App\Form;

use App\Entity\Abonne;
use App\Entity\Emprunt;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Abonne', EntityType::class, [
                'class' => Abonne::class,
                'choice_label' => 'pseudo',
                'placeholder' => ""
            ])
            ->add('Livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => 'IdTitreAuteur',
            ])
            ->add('dateSortie')
            ->add('dateRetour')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
