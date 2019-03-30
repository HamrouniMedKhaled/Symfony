<?php

namespace bonP\badgeBundle\Form\DataTransformer;


use bonP\badgeBundle\Entity\Tag;
use bonP\userBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsTransformer implements DataTransformerInterface {


    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function transform($value)
    {

        return implode(',', $value);
    }


    public function reverseTransform($string)
    {

        $names = array_map('trim', explode(',', $string));

        $tags = $this->em->getRepository(Tag::class)->findBy([
            'name' => $names
        ]);

        $newNames = array_diff($names, $tags);

        foreach ($newNames as $name) {
            $users=$this->em->getRepository(User::class)->findAll();

            //dump($users);
            foreach ($users as $user) {
                if ($user->getUsername()==$name) {
                    $tag = new Tag();
                    $tag->setName($name);
                    $tags[] = $tag;
                }
            }

        }

        return $tags;
    }
}