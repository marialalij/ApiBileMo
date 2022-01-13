<?php

namespace App\Controller;

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
     * @Route("/api/users", name="api_users_list", methods={"GET"})
     * @OA\Get(summary="Get list of BileMo users")
     * @OA\Response(
     *     response=JsonResponse::HTTP_OK,
     *     description="Returns the list of users"
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
     * @Cache(maxage="3600", public=true, mustRevalidate=true)
     *
     * @OA\Tag(name="User")
     */
    public function listOfUsers(UserRepository $repository, SerializerInterface $serializer, Request $request, PaginatorInterface $paginator): JsonResponse
    {

        $liste = $paginator->paginate($repository->findBy(["customer" => $this->getUser()]), $request->query->getInt('page', 1), 5);
        $jsonContent = $serializer->serialize($liste, 'json', SerializationContext::create()->setGroups(['Default', 'users:list']));
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/users/{id}", name="api_user_update", methods={"PUT"})
     * @OA\Put(summary="Update a user")
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
     * @OA\RequestBody(
     *     description="The user data you want to update. Use empty values for unchanged data (e.g. ""password"": """").",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/Json",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 description="User's name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="first_name",
     *                 description="User's first name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 description="User's last name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 description="User's email address",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     * @OA\Tag(name="User")
     */
    public function update(User $user, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        /** @var User $user */
        $updat = $serializer->deserialize($data, User::class, 'json');
        if ($updat->getFirstName()) $user->setFirstName($updat->getFirstName());
        if ($updat->getLastName()) $user->setLastName($updat->getLastName());
        if ($updat->getEmail()) $user->setEmail($updat->getEmail());
        if ($updat->getName()) $user->setName($updat->getName());
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($user, "json", SerializationContext::create()->setGroups(['Default', 'users:list', 'user:read'])),
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }


    /**
     * @Route("/api/users", name="api_user_add", methods={"POST"})
     * @OA\Post(summary="add a user")
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
     */
    public function add(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        /** @var User $user */
        $user = $serializer->deserialize($data, User::class, 'json');
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }
        $user->setCustomer($this->getUser());
        $manager->persist($user);
        $manager->flush();
        return new JsonResponse(
            $serializer->serialize($user, "json", SerializationContext::create()->setGroups(['Default', 'users:list', 'user:read'])),
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }
}
