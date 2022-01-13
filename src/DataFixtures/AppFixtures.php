<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $manager;
    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->Customers();
        $this->Products();

        $manager->flush();
    }

    public function Customers()
    {
        $faker = Factory::create('fr_FR');
        $names = ["free", "orange", "sfr", "clc"];

        foreach ($names as $name) {
            $customer = new Customer();
            $customer->setUsername($name)
                ->setPassword($this->passwordEncoder->encodePassword($customer, $name))
                ->setRoles(["USER"]);

            $this->manager->persist($customer);
        }
    }

    public function Products()
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 25; $i++) {
            $product = new Product;

            $product->setBrand($faker->randomElement(['Apple', 'Samsung', 'xplus', 'htc', 'Huawei']))
                ->setModel($faker->word() . ' ' . $faker->numberBetween(6, 12))
                ->setPrice($faker->randomFloat(2, 250, 1300))
                ->setColor($faker->colorName());
            $this->manager->persist($product);
        }
    }
}
