<?php

namespace App\Controller;

use Throwable;
use JsonException;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class UserController extends AbstractController
{

    /**
     * Return User detail
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get Users detail",
     *     @OA\Parameter(in="path", name="id", required=true, @OA\Schema(type="string"), @OA\Examples(example="int", value="1",summary="An int value")),
     *     @OA\Response(
     *          response=200,
     *     description="OK",
     *     @OA\JsonContent(ref=@Model(type=User::class)),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="not found"),
     *     @OA\Response(response=500, description="Internal error"),
     * ),
     * @OA\Tag(name="User")
     *
     * @param User $user
     * @param UserRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showUser(User $user, UserRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $jsonContent = $serializer->serialize($repository->findBy(['id' => $user->getId()]), 'json', SerializationContext::create()->setGroups(['Default', 'users:list']));
        return new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
    }
}
