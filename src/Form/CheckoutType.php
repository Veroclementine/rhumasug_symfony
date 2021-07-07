<?php

namespace App\Form;

use App\Entity\Carrier;
use App\Entity\Adresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];//on veux recuperer l'adresse du user et le transporteur choisi.

        $builder
            ->add('adress', EntityType::class,[
                'class' => Adresses::class,
                'required' =>true,
                'choices' => $user->getAdresses(),
                'multiple' =>false,
                'expanded' => true
            ])
            ->add('carrier', EntityType::class,[
                'class'=> Carrier::class,
                'required' =>true,
                'multiple' =>false,
                'expanded' => true
            ])
            ->add('informations', TextareaType::class, [
                'required'=>false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurer nous form options ici
            'user'=> array(),
        ]);
    }
}
