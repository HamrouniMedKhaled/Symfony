<?php
/**
 * Created by PhpStorm.
 * User: Fethi Ouerfelli
 * Date: 21/02/2018
 * Time: 22:20
 */

namespace bonP\enseigneBundle\Form;

use bonP\planBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifEnseigneForm extends   AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('description')
            ->add('adresse')
            ->add('image', ImageType::class)
            ->add('enregistrer',SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'bonP\enseigneBundle\Entity\Enseigne'
        ));
    }


    public function getBlockPrefix()
    {
        return 'bonp_enseignebundle_evenement';
    }

}