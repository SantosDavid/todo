<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\ListItems;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ListItemsFixtures extends Fixture
{
    public const REFERENCE = 'list_items';

    public function load(ObjectManager $manager)
    {
        $lisItems = new ListItems();
        $lisItems->setName('product ');
        $lisItems->setDescription('dasdasd');

        $manager->persist($lisItems);
        $manager->flush();

        $this->addReference(self::REFERENCE, $lisItems);
    }
}