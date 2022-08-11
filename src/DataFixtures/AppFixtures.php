<?php

namespace App\DataFixtures;

use App\Entity\Candidat;
use App\Entity\Offres;
use App\Entity\Societe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Finder\Finder;
use SplFileInfo;

class AppFixtures extends Fixture
{
    protected function getDirectoryContent(string $directory): array
    {
        $finder = Finder::create()
            ->in($directory)
            ->depth(0)
            ->name(['*.jpeg', '*.svg', '*.jpg', '*.png'])
            ->sortByName();

        return iterator_to_array($finder, true);
    }




    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {

        $table = [];
        foreach ($this->getDirectoryContent('public/upload') as $file){
            $fileInfos = new SplFileInfo($file);
            $fileName = $fileInfos->getBasename();
            $table[] = $fileName;
        }


        $faker   = Factory::create();
        $slugger = new AsciiSlugger();

        /* Utilisateurs */
        $users = [];

        for ($u = 0; $u < 5; $u++) {
            $user = new Societe();
            $name = $faker->company;
            $user
                ->setName($name)
                ->setLogo($table[random_int(0, count($table) -1)])
                ->setLogoColor($faker->hexColor)
                ->setCity($faker->city)
                ->setLogin($faker->userName)
                ->setRoles(['ROLE_USER'])
                ->setWebsite("https://www.matthiasberthelot.com")
                ->setFirstnameContact($faker->firstName)
                ->setLastnameContact($faker->lastName)
                ->setMail($faker->email)
                ->setSlug(strtolower($slugger->slug($name).uniqid()))
                ->setMobileContact($faker->e164PhoneNumber)
                ->setPassword($this->passwordHasher->hashPassword($user, '1234'));

            $users[] = $user;

            $manager->persist($user);
        }


        /* Administrateur */
        $administrateur = new Societe();
        $administrateur
            ->setName('MatthyB')
            ->setLogo($table[random_int(0, count($table) -1)])
            ->setLogoColor('#d8bc7e')
            ->setCity('Tours')
            ->setLogin('matth')
            ->setRoles(['ROLE_ADMIN'])
            ->setWebsite("https://www.matthiasberthelot.com")
            ->setFirstnameContact($faker->firstName)
            ->setLastnameContact($faker->lastName)
            ->setMail($faker->email)
            ->setSlug(strtolower('MatthyB'.uniqid()))
            ->setMobileContact($faker->e164PhoneNumber)
            ->setPassword($this->passwordHasher->hashPassword($administrateur, '1234'));

        $manager->persist($administrateur);

        /* Userbase */
        $userBase = new Societe();
        $userBase
            ->setName('NameUser')
            ->setLogo($table[random_int(0, count($table) -1)])
            ->setLogoColor($faker->hexColor)
            ->setCity($faker->city)
            ->setLogin('testUser')
            ->setRoles(['ROLE_USER'])
            ->setWebsite("https://www.matthiasberthelot.com")
            ->setFirstnameContact($faker->firstName)
            ->setLastnameContact($faker->lastName)
            ->setMail($faker->email)
            ->setSlug(strtolower("NameUser".uniqid()))
            ->setMobileContact($faker->e164PhoneNumber)
            ->setPassword($this->passwordHasher->hashPassword($userBase, '1234'));

        $manager->persist($userBase);






        /* LES OFFRES */


        $offres = [];
        $offresAdmin = [];
        $offresUser = [];
        $o = array("CDI", "CDD", 'Alternance', "Stage");
        $comp = array("travailleur", "cohésion", "rapidité d'apprentissage");

        for ($u = 0; $u < 40; $u++) {
            $jobTitle = $faker->jobTitle;
            $offre = new Offres();
            $offre
                ->setTitle($jobTitle)
                ->setTypeContrat($o[array_rand($o)])
                ->setDescription($faker->realText())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')))
                ->setProfilDesc($faker->realText())
                ->setProfilComp($comp[array_rand($comp)])
                ->setPosteDesc($faker->realText())
                ->setPosteMission($faker->realText())
                ->setSociete($users[random_int(0, count($users) -1)])
                ->setWebsiteOffre($faker->url)
                ->setSlug(strtolower($slugger->slug($jobTitle).uniqid()));
            $offres[] = $offre;

            $manager->persist($offre);
        }

        for ($i = 0; $i < 3; $i++){
            $jobTitle = $faker->jobTitle;
            $offreBase = new Offres();
            $offreBase
                ->setTitle($jobTitle)
                ->setTypeContrat($o[array_rand($o)])
                ->setDescription($faker->realText())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')))
                ->setProfilDesc($faker->realText())
                ->setProfilComp($comp[array_rand($comp)])
                ->setPosteDesc($faker->realText())
                ->setPosteMission($faker->realText())
                ->setSociete($userBase)
                ->setWebsiteOffre($faker->url)
                ->setSlug(strtolower($slugger->slug($jobTitle).uniqid()));
            $offresUser[] = $offreBase;

            $manager->persist($offreBase);
        }

        for ($i = 0; $i < 3; $i++){
            $jobTitle = $faker->jobTitle;
            $offreBase = new Offres();
            $offreBase
                ->setTitle($jobTitle)
                ->setTypeContrat($o[array_rand($o)])
                ->setDescription($faker->realText())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')))
                ->setProfilDesc($faker->realText())
                ->setProfilComp($comp[array_rand($comp)])
                ->setPosteDesc($faker->realText())
                ->setPosteMission($faker->realText())
                ->setSociete($administrateur)
                ->setWebsiteOffre($faker->url)
                ->setSlug(strtolower($slugger->slug($jobTitle).uniqid()));
            $offresAdmin[] = $offreBase;

            $manager->persist($offreBase);
        }








        // CANDIDATS


        $candidats = [];

        for ($u = 0; $u < 150; $u++) {
            $candidat = new Candidat();
            $candidat
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setMobile($faker->e164PhoneNumber)
                ->setEmail($faker->email)
                ->setCvCandidat('CV-berthelot-matthias-CDA-62e6d6331bf6a.pdf')
                ->setOffres($offres[random_int(0, count($offres) -1)])
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')));

            $manager->persist($candidat);
        }

        for ($u = 0; $u < 20; $u++) {
            $candidat = new Candidat();
            $candidat
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setMobile($faker->e164PhoneNumber)
                ->setEmail($faker->email)
                ->setCvCandidat('CV-berthelot-matthias-CDA-62e6d6331bf6a.pdf')
                ->setOffres($offresUser[random_int(0, count($offresUser) -1)])
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')));

            $manager->persist($candidat);
        }

        for ($u = 0; $u < 20; $u++) {
            $candidat = new Candidat();
            $candidat
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setMobile($faker->e164PhoneNumber)
                ->setEmail($faker->email)
                ->setCvCandidat('CV-berthelot-matthias-CDA-62e6d6331bf6a.pdf')
                ->setOffres($offresAdmin[random_int(0, count($offresAdmin) -1)])
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years', 'now')));

            $manager->persist($candidat);
        }





        $manager->flush();
    }
}
