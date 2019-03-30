<?php

namespace bonP\badgeBundle\Form;

use bonP\badgeBundle\Form\DataTransformer\TagsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsType extends AbstractType {




    /**
     * @var ObjectManager
     */
    private $em;

    public function  __construct(ObjectManager $em)
    {
        $this ->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CollectionToArrayTransformer(),true)
            ->addModelTransformer(new TagsTransformer($this->em),true);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver ->setDefault('attr',[
            'class'=>'tag-input'
        ]);

        $resolver->setDefault('required',false);
    }



    public function getParent()
    {
        return TextType::class;
    }
}
