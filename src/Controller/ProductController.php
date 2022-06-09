<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/product", name="app_product",methods = {"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        $allProducts = $productRepository->findAll();

        dd($allProducts);

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
