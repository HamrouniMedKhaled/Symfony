<?php
/**
 * Created by PhpStorm.
 * User: Fethi Ouerfelli
 * Date: 22/02/2018
 * Time: 06:15
 */

namespace bonP\enseigneBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpMenuForm extends  AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu')
            ->add('prix')

            ->add('modifier',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'bonP\enseigneBundle\Entity\Menu'
        ));
    }


    public function getBlockPrefix()
    {
        return 'bonp_enseignebundle_menu';
    }

}