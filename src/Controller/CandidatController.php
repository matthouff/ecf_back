<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Offres;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CandidatController extends AbstractController
{
    /**
     * @Route("/candidats", name="candidat_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(CandidatRepository $candidatRepository): Response
    {
        if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN'){
            return $this->render('candidat/index.html.twig', [
                'candidats' => $candidatRepository->findAll(),
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }



    /**
     * @Route("offre/{offre}/{candidat}/edit", name="candidat_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function editCandidat(Request $request, CandidatRepository $candidatRepository, Candidat $candidat): Response
    {
        if ($this->getUser()->getId() === $candidat->getOffres()->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){
            $form = $this->createForm(CandidatType::class, $candidat, ['step' => "update_profile"]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $candidatRepository->add($candidat, true);

                return $this->redirectToRoute('offres_show', ['offre' => $candidat->getOffres()->getId(), 'slug' => $candidat->getOffres()->getSociete()->getSlug()], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('candidat/edit_candidat.html.twig', [
                'candidat' => $candidat,
                'candidat_form' => $form
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }



    /**
     * @Route("/{candidat}/delete", name="candidat_delete", methods={"GET", "POST"}, requirements={"candidat": "\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        if ($this->getUser()->getId() === $candidat->getOffres()->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){

            $candidatRepository->remove($candidat, true);
            $this->addFlash('success', 'Le candidat a été supprimé !');
            return $this->redirectToRoute('candidats_index', ['offre' => $candidat->getOffres()->getId()], Response::HTTP_SEE_OTHER);

        }else{
            throw $this->createAccessDeniedException();
        }
    }

    // SUPPRESSION DU CANDIDAT DANS LA PAGE GÉNÉRALE (RETOUR SUR CETTE MÊME PAGE)
    /**
     * @Route("/{candidat}/delete_general", name="candidat_general_delete", methods={"GET", "POST"}, requirements={"candidat": "\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteGeneralCandidat(Candidat $candidat, CandidatRepository $candidatRepository): Response
    {
        if ($this->getUser()->getId() === $candidat->getOffres()->getSociete()->getId() || $this->isGranted('ROLE_ADMIN')){

            $candidatRepository->remove($candidat, true);
            $this->addFlash('success', 'Le candidat a été supprimé !');
            return $this->redirectToRoute('candidat_index', [], Response::HTTP_SEE_OTHER);

        }else{
            throw $this->createAccessDeniedException();
        }
    }
}
