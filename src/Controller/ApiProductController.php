<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiProductController extends AbstractController
{

    /**
     * @Route("/api/product", name="app_api_product",methods={"GET"})
     *
     */
    public function index(ProductRepository $productRepository, SerializerInterface $serializer)
    {

        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), "json", SerializationContext::create()->setGroups(array('list'))),
            JsonResponse::HTTP_OK,
            [],
            true

        );

    }

    /**
     * @Route("/api/product/{id}", name="app_api_product_show",methods={"GET"})
     */
    public function showProduct(Product $product, SerializerInterface $serializer)
    {

        return new JsonResponse(
            $serializer->serialize($product, "json", SerializationContext::create()->setGroups(array('list'))),
            JsonResponse::HTTP_OK,
            [],
            true

        );
    }
}
