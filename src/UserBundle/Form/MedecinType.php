<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('description',TextareaType::class)
            ->add('prix')
            ->add('tel')
            ->add('specialite',ChoiceType::class,array(
                'choices'=>array(
                    'Cardiologue'=>'Cardiologue',
                    'Dentiste'=>'Dentiste',
                    'Dermatologue'=>'Dermatologue',
                    'Generaliste'=>'Généraliste',
                    'Gynecologue Obstetricien'=>'Gynécologue Obstétricien',
                    'Ophtalmologiste'=>'Ophtalmologiste',
                    'ORL)'=>'Oto-Rhino-Laryngologiste (ORL)',
                    'Pediatre'=>'Pédiatre',
                    'Psychiatre'=>'Psychiatre',
                )
            ))
            ->add('adresse')
            ->add('Ajouter',SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Medecin'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_medecin';
    }


}
