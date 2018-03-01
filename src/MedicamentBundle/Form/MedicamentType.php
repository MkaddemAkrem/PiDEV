<?php

namespace MedicamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Nom')))
            ->add('type',TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Type')))
            ->add('prix',TextType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Prix')))
            ->add('notice',TextareaType::class,array('attr'=>array('class'=>'form-control','placeholder'=>'Notice')));

    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MedicamentBundle\Entity\Medicament'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'medicamentbundle_medicamentt';
    }


}