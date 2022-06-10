<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
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
     *
     *  * @Get(
     *     path = "/api/product/{id}",
     *     name = "app_api_product_show",
     *     requirements = {"id"="\d+"}
     * )
     *  @View
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
