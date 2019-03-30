<?php

namespace bonP\enseigneBundle\Form;

use bonP\planBundle\Form\ImageType;
use bonP\userBundle\Form\AdresseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseigneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('description', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
                'attr' => array('cols' => '4', 'rows' => '4')))
            ->add('capacite', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType')
            ->add('adresse',AdresseType::class)
            ->add('image', ImageType::class)
            ->add("categorie",EntityType::class,array('class'=>'bonP\planBundle\Entity\Categorie', 'choice_label'=>'nom', 'multiple'=>false))
            ->add('enregistrer Enseigne',SubmitType::class, array(
                'attr' => array('class' => 'btn btn-success')
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'bonP\enseigneBundle\Entity\Enseigne'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bonp_enseignebundle_enseigne';
    }


}
