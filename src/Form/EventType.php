<?php

namespace App\Form;

use App\Entity\Event;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //null permet de garder le type par défaut qui noramlement est ok
        //attr: pour regler des paramtères html5
            ->add('name',null, array(
                'label'=> "Nom de l'évènement",
                'attr' => array(
                    'placeholder'=>'Dégustation, cours de brassage',
                    //pour gerer les classes bootstrap sur les formulaires
                    'class'=> 'form-control'
    
                ),
            ))
            ->add('description', null, array(
                'label'=>"Description",
                'attr'=>array(
                    'rows'=> 4,
                    'class'=> 'form-control',
                    'placeholder'=> 'Entre 20 et 600 caractères'
                ),
                
            ))

            ->add('startAt',null, array(
                'label'=>'Date et heure de début',
                'invalid_message'=> "la date de début n'est pas valide",
                'date_widget'=>'single_text',
                'time_widget'=>'choice',
                'minutes'=> range(0, 45, 15),
                'attr'=>array(
                    'class'=>'d-flex '
                )
            ))

            ->add('endAt', null, array(
                'label'=>'Date et heure de fin',
                'invalid_message'=> "la date de fin n'est pas valide",
                'date_widget'=>'single_text',
                'time_widget'=>'choice',
                'minutes'=> range(0, 45, 15),
                'attr'=>array(
                    'class'=>'d-flex'
                )
                
            ))

            ->add('picture', UrlType::class, array(
                'label'=>"URL de l'image",
                'invalid_message'=>"L'URL d el'image n'est pas valide",
                'attr'=>array(
                    'class'=> 'form-control',
                )
            ))

            ->add('price',null, array(
                'label'=>'Prix par personne',
                
                'attr'=> array(
                    'class'=> 'form-control',
                    'placeholder'=> 'Laisser vide si évènement gratuit',
                ),
            ))

            ->add('capacity',null, array(
                'label'=>'Nombre maximum de participants',
                
                'attr'=>array(
                    'min'=> 1,
                    'class'=> 'form-control',
                    'placeholder' => 'Laisser vide si nombre de places illimité',
                )
            ))

            //Entity type : sur champ issue d'une table de liaison
            ->add('place', null, array(
                'label'=> "Lieu de l'évènement",
                'choice_label'=> 'name',
                'attr'=>array(
                    'class'=>'d-flex'
                )
            ))


            ->add('categories', null, array(
                'label'=>'Catégories',
                'choice_label'=> 'name',
                'expanded'=> true,
                'attr'=>array(
                    'min'=> 1,
                    'class'=> 'form-control d-flex justify-content-evenly align-items-center',
                )

            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            // empecher la validation auto du form
            'attr'=> array(
                'novalidate'=> 'novalidate',
            )
        ]);
    }
}
