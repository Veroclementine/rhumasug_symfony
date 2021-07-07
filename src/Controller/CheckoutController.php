<?php

namespace App\Controller;

use App\Form\CheckoutType;
use App\Services\CartServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CheckoutController extends AbstractController
{
    private $cartServices;
    private $session;

    public function __construct(CartServices $cartServices, SessionInterface $session)
    {
        $this->cartServices = $cartServices;
        $this->session = $session;
    }
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();//je recupere l'utilisateur connecté
        $cart = $this->cartServices->getFullCart();

        if(!$cart){//si le panier est vide on l'envoie vers 'home'
            return $this->redirectToRoute("home");
        }

        if(!$user->getAdresses()->getValues()){
            $this->addFlash('checkout_message', 'Il faut ajouter une adresse a votre compte pour continuer');
            return $this->redirectToRoute("adresses_new");

        }

        if($this->session->get('checkout_data')){
            return $this->redirectToRoute("checkout_confirm");

        }

         $form = $this->createForm(CheckoutType::class,null,['user' => $user]);

        //  $form->handleRequest($request);

        // //traitement du formulaire


        return $this->render('checkout/index.html.twig',[
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/confirm", name="checkout_confirm")
     */
    public function confirm(Request $request): Response{
        $user = $this->getUser();
        $cart = $this->cartServices->getFullCart();

        if(!$cart){
            return $this->redirectToRoute("home");
        }

        if(!$user->getAdresses()->getValues()){
            $this->addFlash('checkout_message', 'Il faut ajouter une adresse a votre compte pour continuer');
            return $this->redirectToRoute("adresses_new");

        }

         $form = $this->createForm(CheckoutType::class,null,['user' => $user]);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid() || $this->session->get('checkout_data')){

            if($this->session->get('checkout_data')){
                $data = $this->session->get('checkout_data');
            }else{
                $data = $form->getData();
                $this->session->set('checkout_data', $data);
            }

            $adress = $data['adress'];
            $carrier = $data['carrier'];
            $information = $data['informations'];


            return $this->render('checkout/confirm.html.twig',[
                'cart' => $cart,
                'adress' => $adress,
                'carrier' => $carrier,
                'informations'=> $information,
                'checkout' => $form->createView()
            ]);

         }

         return $this->redirectToRoute('checkout');
    }
}
