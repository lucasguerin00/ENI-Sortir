<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('duree', IntegerType::class)
            ->add('dateLimiteInscription', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionMax', IntegerType::class)
            ->add('infosSortie', TextType::class)
//            ->add('idSite', EntityType::class, [
//                'class' => Site::class,
//                'choice_label' => 'nom',
//            ])
//            ->add('idLieu', EntityType::class, [
//                'class' => Lieu::class,
//                'choice_label' => 'nom',
//            ])
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle'
            ])
            ->add('idOrganisateur', EntityType::class, [
                'class' => Participant::class,
                'choice_label' => 'pseudo',
            ])
//            ->add('participants', EntityType::class, [
//                'class' => Participant::class,
//                'choice_label' => 'id',
//                'multiple' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
