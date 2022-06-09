<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiProductController extends AbstractController
{
    // private $serializer;

    // public function __construct(
    //     SerializerInterface $serializer
    // ) {
    //     $this->serialize = $serializer;
    // }

    /**
     * @Route("/api/product", name="app_api_product",methods={"GET"})
     *
     */
    public function index(ProductRepository $productRepository, SerializerInterface $serializer)
    {

        // $data = $this->get('jms_serializer')->serialize($allProducts, 'json');
        // // // dd($data);
        // $response = new Response($data);
        // $response->headers->set('Content-Type', 'application/json');

        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), "json"),
            JsonResponse::HTTP_OK,
            [],
            true

        );

        // return $this->render('api_product/index.html.twig', [
        //     'controller_name' => 'ApiProductController',
        // ]);
    }

    /**
     * @Route("/api/product/{id}", name="app_api_product_show",methods={"GET"})
     */
    public function showProduct(ProductRepository $productRepository)
    {

        // $oneProduct = $productRepository->findOneBy(['id' => $id]);
        // dd($oneProduct);
        // $data = $this->get('jms_serializer')->serialize($allProducts, 'json', SerializationContext::create()->setGroups(array('list')));

        // $response = new Response($data);
        // $response->headers->set('Content-Type', 'application/json');

        // return $response;

        return $this->render('api_product/index.html.twig', [
            'controller_name' => 'ApiProductController',
        ]);
    }
}
