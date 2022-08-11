<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Offres;
use App\Form\CandidatType;
use App\Form\OffresType;
use App\Repository\CandidatRepository;
use App\Repository\OffresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/offres")
 */
class OffresController extends AbstractController
{
    /**
     * @Route("/", name="offres_index", methods={"GET"})
     * is_granted('ROLE_ADMIN')
     */
    public function index(OffresRepository $offresRepository, Request $request): Response
    {
        if (!$this->isGranted('ROLE_USER') || $this->isGranted('ROLE_ADMIN')){

            return $this->render('offres/index.html.twig', [
                'offres' => $offresRepository->findAll(),
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/{offre}/edit", name="offres_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Offres $offre, OffresRepository $offresRepository): Response
    {
        if ($this->getUser()->getId() === $offre->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){


            $form = $this->createForm(OffresType::class, $offre);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $offresRepository->add($offre, true);

                return $this->redirectToRoute('offres_show', ['slug' => $offre->getSociete()->getSlug(), 'offre' => $offre->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('offres/edit.html.twig', [
                'offre' => $offre,
                'offre_form' => $form,
            ]);

        }else{
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/{offre}/delete", name="offres_delete", methods={"GET", "POST"}, requirements={"offre": "\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Offres $offre, OffresRepository $offresRepository): Response
    {
        if ($this->getUser()->getId() === $offre->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){

            $offresRepository->remove($offre, true);
            $this->addFlash('success', 'L\'offre a été supprimé !');
            return $this->redirectToRoute('offres_societe_index', ["slug" => $offre->getSociete()->getSlug()], Response::HTTP_SEE_OTHER);

        }else{
            throw $this->createAccessDeniedException();
        }
    }

    // SUPPRESSION DE L'OFFRE DANS LA PAGE GÉNÉRALE (RETOUR SUR CETTE MÊME PAGE)
    /**
     * @Route("/{offre}/suppression", name="offres_general_delete", methods={"GET", "POST"}, requirements={"offre": "\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteGeneral(Offres $offre, OffresRepository $offresRepository): Response
    {
        if ($this->getUser()->getId() === $offre->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){

            $offresRepository->remove($offre, true);
            $this->addFlash('success', 'L\'offre a été supprimé !');
            return $this->redirectToRoute('offres_index', [], Response::HTTP_SEE_OTHER);

        }else{
            throw $this->createAccessDeniedException();
        }
    }








    /**
     * @Route("/{offre}/candidats", name="candidats_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function indexCandidat(Offres $offre): Response
    {
        if ($this->getUser()->getId() === $offre->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){
            return $this->render('candidat/index_candidats.html.twig', [
                'offre' => $offre
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }



    /**
     * @Route("/{offre}/new-candidat", name="candidat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Offres $offre, CandidatRepository $candidatRepository, SluggerInterface $slugger): Response
    {
        if (!$this->getUser() || $this->getUser()->getId() === $offre->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){

            $candidat = new Candidat();
            $formCandidat = $this->createForm(CandidatType::class, $candidat);
            $formCandidat->handleRequest($request);

            if ($formCandidat->isSubmitted() && $formCandidat->isValid()) {
                $cv = $formCandidat->get('cv_candidat')->getData();
                $cvBaseName = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
                $safeLogoName = $slugger->slug($cvBaseName);
                $newCvName = $safeLogoName.'-'.uniqid().'.'.$cv->guessExtension();

                $cv->move(
                    $this->getParameter('image_directory'),
                    $newCvName
                );

                $candidat->setCvCandidat($newCvName)
                    ->setOffres($offre)
                    ->setCreatedAt(new \DateTimeImmutable('now'));
                $candidatRepository->add($candidat, true);

                $this->addFlash('success', 'Votre candidature à bien été envoyé !');

                return $this->redirectToRoute('offres_show', ['slug' => $candidat->getOffres()->getSociete()->getSlug(), 'offre' => $candidat->getOffres()->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('candidat/new_candidat.html.twig', [
                'candidat' => $candidat,
                'candidat_form' => $formCandidat->createView(),
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/{offre}/{candidat}", name="candidat_show", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Candidat $candidat, Offres $offre): Response
    {
        if ($this->getUser()->getId() === $offre->getSociete()->getId() && $this->getUser()->getId() === $candidat->getOffres()->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){
            return $this->render('candidat/show.html.twig', [
                'offre' => $offre,
                'candidat' => $candidat,
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }
}
