<?php

namespace App\Form;

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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de début de la sortie',
                'widget' => 'single_text',
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée de la sortie (en minutes)',
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre d\'inscriptions maximum',
            ])
            ->add('infosSortie', TextType::class)
            ->add('idSite', EntityType::class, [
                'class' => Site::class,
                'label' => 'Site de la sortie',
                'choice_label' => 'nom',
            ])
            ->add('idLieu', EntityType::class, [
                'class' => Lieu::class,
                'label' => 'Lieu de la sortie',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un lieu',
            ])
            ->add('rue', TextType::class, [
                'mapped' => false,
                'required' => false,
                'disabled' => true,
            ])
            ->add('idOrganisateur', EntityType::class, [
                'class' => Participant::class,
                'label' => 'Organisateur',
                'choice_label' => 'pseudo',
            ])
        ;

        // remplissage au chargement (édition)
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $sortie = $event->getData();
            $form = $event->getForm();

            $lieu = $sortie?->getIdLieu();
            if ($lieu) {
                $form->get('rue')->setData($lieu->getRue());
            }
        });

        // lorsque le champ idLieu est soumis -> copier la rue dans le champ 'rue' (sans ré-ajouter le champ)
        $builder->get('idLieu')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $lieu = $event->getForm()->getData();         // l'entité Lieu choisie
            $form = $event->getForm()->getParent();       // le formulaire parent (Sortie)

            if ($form->has('rue')) {
                $form->get('rue')->setData($lieu?->getRue());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
