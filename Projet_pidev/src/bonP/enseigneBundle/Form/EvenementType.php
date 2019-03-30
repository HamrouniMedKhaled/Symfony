<?php

namespace bonP\enseigneBundle\Form;

use bonP\planBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('description', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
                'attr' => array('cols' => '4', 'rows' => '4')))
            ->add('date','Symfony\Component\Form\Extension\Core\Type\DateType', array(
                'format' => 'dd-MM-yyyy HH:mm',
                'attr' => ['class' => 'form-control'],
            ))
            ->add('image',ImageType::class)
            ->add('Ajouter',SubmitType::class, array(
                'attr' => array('class' => 'btn btn-success')
            ));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'bonP\enseigneBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bonp_enseignebundle_evenement';
    }


}
