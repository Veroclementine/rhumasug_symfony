<?php

namespace App\Controller;

use App\Entity\Adresses;
use App\Form\AdressesType;
use App\Repository\AdressesRepository;
use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adresses")
 */
class AdressesController extends AbstractController
{
    private $session;

    public function __construct( SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="adresses_index", methods={"GET"})
     */
    public function index(AdressesRepository $adressesRepository): Response
    {
        return $this->render('adresses/index.html.twig', [
            'adresses' => $adressesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="adresses_new", methods={"GET","POST"})
     */
    public function new(Request $request, CartServices $cartServices): Response
    {
        $adress = new Adresses();
        $form = $this->createForm(AdressesType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this ->getUser(); 
            $adress->setUser($user);  //ça nous recuperer l'utilisateur qui est connecté
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adress);
            $entityManager->flush();

            if($cartServices->getFullCart()){
                return $this->redirectToRoute('account');
            }

            $this->addFlash('adress_message', 'Votre adresse a été bien enregistrée');
            return $this->redirectToRoute('account');
        }

        return $this->render('adresses/new.html.twig', [
            'adress' => $adress,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="adresses_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adresses $adress): Response
    {
        $form = $this->createForm(AdressesType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if($this->session->get('checkout_data')){
                $data = $this->session->get('checkout_data');
                $data['adress'] = $adress;
                $this->session->set('checkout_data', $data);
                return $this->redirectToRoute('checkout_confirm');
            }

            $this->addFlash('adress_message', 'Votre adresse a été bien modifiée');
            return $this->redirectToRoute('account');
        }

        return $this->render('adresses/edit.html.twig', [
            'adress' => $adress,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="adresses_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Adresses $adress): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adress);
            $entityManager->flush();
            $this->addFlash('adress_message', 'Votre adresse a été supprimée');
        }

        return $this->redirectToRoute('account');
    }
}
