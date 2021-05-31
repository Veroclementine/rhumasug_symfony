<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchProductType;
use App\Repository\HomeSliderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $repoProduct,
                          HomeSliderRepository $repoHomeSlider): Response
    {
        $products = $repoProduct->findAll();
        $homeSlider = $repoHomeSlider->findBy(['isDisplayed'=>true]);

        $productPlusVendu = $repoProduct->findByPlusVendu(1);
        $productOffert = $repoProduct->findByOffert(1);
        $productNewProduct = $repoProduct->findByNewProduct(1);
    
        // dd([$productPlusVendu, $productOffert, $productNewProduct]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productPlusVendu' => $productPlusVendu,
            'productOffert' => $productOffert,
            'NewProduct' => $productNewProduct,
            'homeSlider' => $homeSlider,
        ]);
    }

/**
 * @Route("/product/{slug}", name="product_details")
 */
    public function show(?Product $product): Response{

        if(!$product){
            return $this->redirectToRoute("home");
        }
        return $this->render("home/single_product.html.twig",[
            'product' => $product
        ]);
    }

    /**
     * @Route("/produits", name="produits")
     */
    public function produits(ProductRepository $repoProduct): Response
    {
        $products = $repoProduct->findAll();
        $form = $this->createForm(SearchProductType::class, null);

        return $this->render('home/produits.html.twig', [
            'products' => $products,
            'search' => $form->createView()

        ]);
    }

}
