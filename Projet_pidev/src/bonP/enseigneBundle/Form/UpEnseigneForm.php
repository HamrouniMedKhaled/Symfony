<?php
/**
 * Created by PhpStorm.
 * User: Fethi Ouerfelli
 * Date: 22/02/2018
 * Time: 07:05
 */

namespace bonP\enseigneBundle\Form;

use Alpha\A;
use bonP\planBundle\Form\ImageType;
use bonP\userBundle\Form\AdresseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpEnseigneForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('description')
            ->add('capacite')
            ->add('adresse',AdresseType::class)
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
        return 'bonp_enseignebundle_menu';
    }


}