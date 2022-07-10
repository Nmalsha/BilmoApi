<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiProductController extends AbstractController
{

    /**
     * @Route("/api/product", name="app_api_product",methods={"GET"})
     *  @OA\Response(
     *     response=200,
     *     description="Returns the products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ApiProductController::class))
     *     )
     * )
     *  @OA\Parameter(
     *     name="products",
     *     in="query",
     *     description="",
     *     @OA\Schema(type="string")
     * )
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
     *   *  @OA\Response(
     *     response=200,
     *     description="Returns the products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ApiProductController::class))
     *     )
     * )
     *  @OA\Parameter(
     *     name="products",
     *     in="query",
     *     description="",
     *     @OA\Schema(type="string")
     * )
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
