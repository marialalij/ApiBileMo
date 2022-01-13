<?php

namespace App\Controller;

use Throwable;
use JsonException;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Knp\Component\Pager\PaginatorInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{

    /**
     * @Route("/api/users/{id}", name="api_user_detail", methods={"GET"})
     * @OA\Get(summary="details of a user")
     * @OA\Response(
     *     response=JsonResponse::HTTP_OK,
     *     description="Update a user and returns it"
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
     *     description="User not found"
     * )
     * @OA\Tag(name="User")
     *
     * @param User $user
     * @param UserRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(User $user, UserRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $jsonContent = $serializer->serialize($repository->findBy(['id' => $user->getId()]), 'json', SerializationContext::create()->setGroups(['Default', 'users:list']));
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/users/{id}", name="api_user_delete", methods={"DELETE"})
     * @OA\Delete(summary="Delete a user")
     * @OA\Response(
     *     response=JsonResponse::HTTP_NO_CONTENT,
     *     description="Delete a user"
     * )
     * @OA\Response(
     *     response=JsonResponse::HTTP_UNAUTHORIZED,
     *     description="Unauthorized request"
     * )
     * @OA\Response(
     *     response=JsonResponse::HTTP_NOT_FOUND,
     *     description="User not found"
     * )
     * @OA\Tag(name="User")
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->json("", Response::HTTP_NO_CONTENT);
    }


    /**
     * Return list Users of a Client
     * @Route("/api/users", name="api_user_list", methods={"GET"})
     * @OA\GET(summary="add a user")
     * @OA\Response(
     *     response=JsonResponse::HTTP_OK,
     *     description="list of users"
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
     *     description="User not found"
     * )
     *
     * @param UserRepository $repository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return JsonResponse
     */
    public function listOfUsers(UserRepository $repository, SerializerInterface $serializer, Request $request, PaginatorInterface $paginator): JsonResponse
    {

        $liste = $paginator->paginate($repository->findBy(["customer" => $this->getUser()]), $request->query->getInt('page', 1), 5);
        $jsonContent = $serializer->serialize($liste, 'json', SerializationContext::create()->setGroups(['Default', 'users:list']));
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
}
