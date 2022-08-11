<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Offres;

class OffresTest extends KernelTestCase
{


    // On test la validité de notre entité "Offres"

    public function testValidEntity(){
        $code = (new Offres())
            ->setTitle('test')
            ->setTypeContrat('CDD')
            ->setDescription('descriptionTest')
            ->setProfilDesc('description Profil Test')
            ->setProfilComp('Competences Test')
            ->setPosteDesc('description Poste Test')
            ->setPosteMission('Mission Test')
            ->setWebsiteOffre('websiteTest');
        self::bootKernel();
        $error = self::$container->get('validator')->validate($code);
        $this->assertCount(0, $error);
    }

}
