<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class OffresControllerTest extends WebTestCase
{

    // Ici on test pour savoir si le status retourne bien "200"

    public function TestIndexOffre(){
        $offre = static::createClient();
        $offre->request('GET', '/offres/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}