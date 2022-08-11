<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class SocieteControllerTest extends WebTestCase
{


    // On test la redirection vers le login si un utilisateur essaye d'aller sur l'index des sociétés réservé à l'admin

    public function testRedirectToLogin(){
        $societe = static::createClient();
        $societe->request('GET', '/societes/');
        $this->assertResponseRedirects('/login');
    }




    //Ne fonctionne pas


    /*public function testCreateSociete(){

        $uploadedFile = new UploadedFile( __DIR__.'/../../public/upload/brand-dodge.svg', 'brand-dodge.svg');

        $client = static::createClient();
        $crawler = $client->request('GET', '/societes/new');

        $buttonCrawlerNode = $crawler->selectButton('societe');
        $form = $buttonCrawlerNode->form();

        $form['societe[login]'] = 'Testeur';
        $form['societe[password]'] = '1234';
        $form['societe[name]'] = 'Testeur';
        $form['societe[logo]'] = $uploadedFile;
        $form['societe[logo_color]']   = '#FFF';
        $form['societe[city]']   = 'Tours';
        $form['societe[website]']   = 'root.coom';
        $form['societe[firstname_contact]']   = 'root';
        $form['societe[lastname_contact]']   = 'root';
        $form['societe[mail]']   = 'root@gsdf.fre';

        $client->submit($form);
        $client->followRedirects(true);

        $this->assertResponseStatusCodeSame(303);
        $this->assertResponseRedirects('/login');
    }*/

}