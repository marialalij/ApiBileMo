<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use OpenApi\Annotations as OA;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ProductController extends AbstractController
{

    /**
     * show details of a user
     * @Route("/api/products/{id}", name="api_product_details", methods={"GET"})
     * @OA\Get(summary="Get details of a product")
     * @OA\Response(
     *     response=JsonResponse::HTTP_OK,
     *     description="Returns a product"
     * )
     * @OA\Response(
     *     response=JsonResponse::HTTP_NOT_FOUND,
     *     description="Product not found"
     * )
     * 
     * @OA\Response(
     *     response=JsonResponse::HTTP_BAD_REQUEST,
     *     description="Bad Json syntax or incorrect data"
     * )
     * 
     * @OA\Response(
     *     response=JsonResponse::HTTP_UNAUTHORIZED,
     *     description="Unauthorized request"
     * )
     * @OA\Response(
     *     response=JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
     *     description="HTTP_INTERNAL_SERVER_ERROR request"
     * )
     * @Cache(maxage="3600", public=true, mustRevalidate=true)
     * 
     * @OA\Tag(name="Product")
     *
     */
    public function showProduct(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $jsonContent = $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(["Default", "product:detail"]));
        if (empty($jsonContent)) {
            throw new NotFoundHttpException();
        }
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    /**
     * show list of users 
     * 
     * @Route("/api/products", name="api_product_list", methods={"GET"})
     * @OA\Get(summary="Get list of BileMo products")
     * @OA\Response(
     *     response=JsonResponse::HTTP_OK,
     *     description="Returns the list of products"
     * )
     * @OA\Response(
     *     response=JsonResponse::HTTP_BAD_REQUEST,
     *     description="Bad Json syntax or incorrect data"
     * )
     * @OA\Response(
     *     response=JsonResponse::HTTP_UNAUTHORIZED,
     *     description="Unauthorized request"
     * )
     * @OA\Response(
     *     response=JsonResponse::HTTP_NOT_FOUND,
     *     description="Product not found"
     * )
     * 
     * @OA\Response(
     *     response=JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
     *     description="HTTP_INTERNAL_SERVER_ERROR request"
     * )
     * @Cache(maxage="3600", public=true, mustRevalidate=true)
     * 
     * @OA\Tag(name="Product")
     * 
     */
    public function listproduct(ProductRepository $repo, SerializerInterface $serializer, Request $request, PaginatorInterface $paginator): JsonResponse
    {
        $liste = $paginator->paginate($repo->findAll(), $request->query->getInt('page', 1), 5);
        $jsonContent = $serializer->serialize($liste, 'json', SerializationContext::create()->setGroups(["Default", "product:read"]));
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
}
