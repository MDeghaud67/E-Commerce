<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 20; $i++)
        {
            $product = new Product();
            $product->setName('Computer Component '.$i);
            $product->setDescription('Best computer component in the shop!');
            $product->setPhoto('https://path/to/image.png');
            $product->setPrice(13.37);
            $manager->persist($product);
            $this->addReference('Computer Component n°'. $i, $product);
        }
        $manager->flush();
    }
}
