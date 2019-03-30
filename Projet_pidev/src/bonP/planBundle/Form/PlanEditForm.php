<?php

namespace bonP\planBundle\Form;

use bonP\planBundle\Entity\Plan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("titre")
            ->add("description")
            ->add('image', ImageType::class, array('required' => false))
            ->setMethod('POST')
            ->add('Enregitrer le plan',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Plan::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'plan_bundle_plan_form';
    }
}
