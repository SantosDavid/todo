<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ItemsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $item = new Item();
        $item->setName('asdasd');
        $item->setConcluded(0);

        $item->setListItems($this->getReference(ListItemsFixtures::REFERENCE));

        $manager->persist($item);

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            ListItemsFixtures::class,
        ];
    }
}