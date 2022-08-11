<?php

namespace App\Controller;

use App\Repository\OffresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index", methods={"GET"})
     */
    public function index(OffresRepository $offresRepository): Response
    {
        $offres = $offresRepository->findBestFourOffres();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'offres' => $offres
        ]);
    }


}
