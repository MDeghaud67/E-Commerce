<?php

namespace App\DataFixtures;

use App\Entity\Order;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            ProductFixtures::class
        ];
    }
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 5; $i++){
            $order = new Order;
            $order->setTotalPrice(42.01);
            $order->setCreationDate(new DateTime("2021-04-01 08:32:00Z"));
            $order->addProduct($this->getReference('Computer Component n°'. $i));
            $manager->persist($order);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
