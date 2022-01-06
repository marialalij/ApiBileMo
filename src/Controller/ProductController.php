<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ProductController extends AbstractController
{

    /**
     * detail d'un seul produit
     *
     * @Route("/api/product/{id}", name="products", methods={"GET"})
     *
     * @OA\Tag(name="Product")
     * @param Product $product
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $jsonContent = $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(["Default", "product:detail"]));
        if (empty($jsonContent)) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    /**
     * la liste des produits
     *
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get products list",
     *     @OA\Response(
     *          response=200,
     *     description="OK",
     *     @OA\JsonContent(ref=@Model(type=Product::class)),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="not found"),
     *     @OA\Response(response=500, description="Internal error"),
     * ),
     * @OA\Tag(name="Product")
     * @param ProductRepository $repo
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function listproduct(ProductRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $liste = $repo->findAll();
        $jsonContent = $serializer->serialize($liste, 'json', SerializationContext::create()->setGroups(["Default", "product:read"]));
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
}
