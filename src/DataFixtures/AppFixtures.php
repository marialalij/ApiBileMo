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
        $providers = ["free", "orange", "sfr", "clc"];

        foreach ($providers as $provider) {
            $customer = new Customer();
            $customer->setUsername($provider)
                ->setPassword($this->passwordEncoder->encodePassword($customer, $provider))
                ->setRoles(["USER"]);

            $this->manager->persist($customer);
        }
    }

    public function Products()
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 25; $i++) {
            $product = new Product;

            $product->setBrand($faker->randomElement(['Apple', 'Samsung', 'Google', 'OnePlus', 'Huawei']))
                ->setModel($faker->word() . ' ' . $faker->numberBetween(6, 12))
                ->setPrice($faker->randomFloat(2, 250, 1300))
                ->setColor($faker->colorName());
            $this->manager->persist($product);
        }
    }
}
