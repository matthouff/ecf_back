<?php

namespace App\Controller;

use App\Entity\Offres;
use App\Entity\Societe;
use App\Form\OffresType;
use App\Form\SocieteType;
use App\Repository\OffresRepository;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/societe")
 */
class SocieteController extends AbstractController
{

    /**
     * @Route("s/", name="societe_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(SocieteRepository $societeRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')){
            return $this->render('societe/index.html.twig', [
                'societes' => $societeRepository->findAll(),
            ]);
        }else{
            return $this->redirectToRoute('home_index', [], Response::HTTP_SEE_OTHER);
        }
    }



    /**
     * @Route("/new", name="societe_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SocieteRepository $societeRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $societe = new Societe();
        $form = $this->createForm(SocieteType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();

            $logo = $form->get('logo')->getData();
            $logoBaseName = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
            $safeLogoName = $slugger->slug($logoBaseName);
            $newLogoName = $safeLogoName.'-'.uniqid().'.'.$logo->guessExtension();

            $logo->move(
                $this->getParameter('image_directory'),
                $newLogoName
            );

            $societe->setLogo($newLogoName);
            $societe->setSlug(strtolower($slugger->slug($societe->getName())).uniqid());
            $societe->setRoles(['ROLE_USER']); // Même si les tables peuvent rester vide en BDD j'ai prefere afficher le rôle quand même
            $societe->setPassword($passwordHasher->hashPassword($societe, $societe->getPassword()));
            $societeRepository->add($societe, true);

            $this->addFlash('success', 'Merci de votre inscription, votre compte à bien été créé !');

            return $this->redirectToRoute('societe_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('societe/new.html.twig', [
            'societe' => $societe,
            'societe_form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{slug}", name="societe_show", methods={"GET"})
     */
    public function show(Societe $societe, $slug): Response
    {
        if (!$this->getUser() || $this->getUser()->getId() === $societe->getId() || $this->isGranted('ROLE_ADMIN')){
            return $this->render('societe/show.html.twig', [
                'societe' => $societe,
                'slug' => $societe->getSlug()
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }



    /**
     * @Route("/{slug}/edit", name="societe_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Societe $societe, SocieteRepository $societeRepository): Response
    {
        if ($this->getUser()->getId() === $societe->getId() || $this->isGranted('ROLE_ADMIN')){

            $form = $this->createForm(SocieteType::class, $societe, ['step' => "update_profile"]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $societeRepository->add($societe, true);
                $this->addFlash('success', 'Modifications réussi !');

                return $this->redirectToRoute('societe_show', ['slug' => $societe->getSlug()], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('societe/edit.html.twig', [
                'societe' => $societe,
                'societe_form' => $form,
                'slug' => $societe->getSlug()
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }



    /**
     * @Route("/{slug}/modify-password", name="societe_update_password", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function resetPassword(Request $request, Societe $societe, SocieteRepository $societeRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        if($this->getUser()->getId() === $societe->getId() || $this->isGranted('ROLE_ADMIN') ){

            // 'update_profile' -> formtype
            $form = $this->createForm(SocieteType::class, $societe, ['step' => "update_password"]);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                $societe->setPassword($passwordHasher->hashPassword($societe, $societe->getPassword()));
                $societeRepository->add($societe, true);
                $this->addFlash('success', 'Modifications réussi !');

                return $this->redirectToRoute('societe_show', ['slug' => $societe->getSlug()], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('societe/edit.html.twig', [
                'societe' => $societe,
                'societe_form' => $form,
                'slug' => $societe->getSlug()
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }



    /**
     * @Route("/{societe}/delete", name="societe_delete", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Societe $societe, SocieteRepository $societeRepository): Response
    {
        if ($this->getUser()->getId() === $societe->getId())
        {

            if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->request->get('_token'))) {
                $societeRepository->remove($societe, true);
            }
            // Permet de supprimer son propre compte et revenir à l'accueil sans avoir d'erreur
            $session = new Session();
            $session->invalidate();
            $session->getFlashBag()->add('success', 'Votre compte à bien été supprimé !');
            return $this->redirectToRoute('home_index', [], Response::HTTP_SEE_OTHER);

        } else if ($this->getUser()->getId() === $societe->getId() || $this->isGranted('ROLE_ADMIN')){

            if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->request->get('_token'))) {
                $societeRepository->remove($societe, true);
                $this->addFlash('success', 'La société "'. $societe->getName() .'" à bien été supprimé !');
            }
            return $this->redirectToRoute('societe_index', [], Response::HTTP_SEE_OTHER);

        }else{
            throw $this->createAccessDeniedException();
        }
    }






    // ZONE OFFRE / SOCIÉTÉS



    /**
     * @Route("/{slug}/new-offre", name="offre_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function newOffre(Request $request, OffresRepository $offresRepository, Societe $societe, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        if ($this->getUser()->getId() === $societe->getId() || $this->isGranted('ROLE_ADMIN')){

            $offre = new Offres();
            $form = $this->createForm(OffresType::class, $offre);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $offre
                    ->setSociete($societe)
                    ->setCreatedAt(new \DateTimeImmutable('now'));

                $offre->setSlug(strtolower($slugger->slug($offre->getTitle())).uniqid());
                $offresRepository->add($offre, true);

                return $this->redirectToRoute('offres_societe_index', ['slug' => $societe->getSlug()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('societe/new_offre.html.twig', [
                'societe' => $societe,
                'offre' => $offre,
                'offre_form' => $form->createView(),
                'slug' => $societe->getSlug()
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }



    /**
     * @Route("/{slug}/offres", name="offres_societe_index", methods={"GET", "POST"})
     */
    public function indexOffresSociete(Societe $societe): Response
    {
        if (!$this->getUser() || $this->getUser()->getId() === $societe->getId() || $this->isGranted('ROLE_ADMIN')){
            return $this->render('offres/index_societe_offres.html.twig', [
                'societe' => $societe,
                'slug' => $societe->getSlug()
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/{slug}/{offre}/show", name="offres_show", methods={"GET", "POST"})
     */
    public function showOffres(Offres $offre, Societe $societe): Response
    {
        if (!$this->getUser() || $this->getUser()->getId() === $societe->getId() && $this->getUser()->getId() === $offre->getSociete()->getId()  || $this->isGranted('ROLE_ADMIN') ){
            return $this->render('offres/show.html.twig', [
                'societe' => $societe,
                'offre' => $offre,
                'slug' => $societe->getSlug()
            ]);
        }else{
            throw $this->createAccessDeniedException();
        }
    }
}
