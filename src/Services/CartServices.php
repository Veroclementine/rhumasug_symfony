<?php
namespace App\Services;

use App\Repository\ProductRepository;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices{
    
    private $session;
    private $repoProduct;
    private $tva = 0.2;

    public function __construct(SessionInterface $session, ProductRepository $repoProduct)//injecter la session dans le parametres
    {
        $this->session = $session;//inicialicer la session
        $this->repoProduct =$repoProduct;
    }

    public function addToCart($id){//methode pour ajouter 1 prod au panier
       $cart = $this->getCart();
       if(isset($cart[$id])){
           //produit deja dans le panier
           $cart[$id]++;
       }else{
           //le produit n'est pas encore dans le panier
           $cart[$id] = 1;
       }
       $this->updateCart($cart);

    }

    public function deleteFromCart($id){//methode pour suprimer 1 prod du panier
        $cart = $this->getCart();
        if(isset($cart[$id])){
            //produit deja dans le panier
            if($cart[$id] > 1){
                //produit existe plus d'une fois
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
            $this->updateCart($cart);
        }
    }

    public function deleteAllToCart($id){//methode pour vider le panier
        $cart = $this->getCart();
        if(isset($cart[$id])){
                unset($cart[$id]);
            $this->updateCart($cart);
        }
    }

    public function deleteCart(){
        $this->updateCart([]);
    }

    public function updateCart($cart){//methode pour mettre a jour le panier
        $this->session->set('cart', $cart);
        $this->session->set('cartData', $this->getFullCart());//on prend la session et on met la clé cartData, on me le methode pour recuperer le full cart getFullCart
    }

    public function getCart(){
        return $this->session->get('cart', []);//recuperer le contenu de une session
    }

    public function getFullCart(){
        $cart = $this->getCart();
        $fullCart =[];
        $quantity_cart = 0;
        $subTotal = 0;  
       
        foreach ($cart as $id => $quantity) {
            $product = $this->repoProduct->find($id);
            # code...
            if($product){
                //Produit recuperé avec succes
                $fullCart['products'] []=
                [
                    "quantity" => $quantity,
                    "product" => $product
                ];
                $quantity_cart += $quantity;
                $subTotal += $quantity * $product->getPrix()/100;
            }else{
                // id incorrecte
                $this->deleteFromCart($id);
            }
        }
           $fullCart['data'] = [
               "quantity_cart" => $quantity_cart,
               "subTotalHT" => $subTotal,
               "taxe" => round($subTotal*$this->tva, 2),
               "subTotalTTC" => round(($subTotal + ($subTotal*$this->tva)), 2)
           ];

        return $fullCart;
    }

}