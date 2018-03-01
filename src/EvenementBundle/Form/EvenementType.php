<?php

namespace EvenementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Titre')))
            ->add('localite',TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Localité')))

            ->add('lat',TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'lat')))

            ->add('lng',TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'lng')))
            ->add('date',DateTimeType::class,array('data'=>new \DateTime("now"),'widget' => "single_text", 'attr' =>array('class' => 'form-control')))
            ->add('description',TextareaType::class,array('attr'=>array('class'=>'ckeditor','placeholder'=>'Description')))
            ->add('image', FileType::class, array('attr' => array('class' => 'form-control'),'data_class' => null));
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EvenementBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'evenementbundle_evenement';
    }


}
