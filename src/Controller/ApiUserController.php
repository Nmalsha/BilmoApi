<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CustomerRepository;
// use App\Exception\ResourceValidationException;
use App\Repository\UserRepository;
use App\Service\DecodeToken;
use Doctrine\ORM\EntityManagerInterface;
//use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
//use FOS\RestBundle\Controller\Annotations\QueryParam;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

//use Symfony\Component\Serializer\SerializerInterface;

class ApiUserController extends AbstractController
{

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, JWTEncoderInterface $jwtEncoder, DecodeToken $decodeToken)
    {
        $this->jwtManager = $jwtManager;
        $this->decodeToken = $decodeToken;
        $this->tokenStorageInterface = $tokenStorageInterface;
        // $this->jwtEncoder = $jwtEncoder;
    }
    /**
     * @Route("/api/users", name="app_api_users", methods={"GET"})
     * @return JsonResponse
     *
     */
    public function index(Request $request, CustomerRepository $customerRepository, UserRepository $userRepository, SerializerInterface $serializer, JWTEncoderInterface $jwtEncoder): Response
    {

        //decoding token
        //  $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        $decodedJwtToken = $this->decodeToken->loadUserInfo();

        $userEmail = $decodedJwtToken['email'];
        if ($this->decodeToken->userCan($userEmail, "ROLE_ADMIN")) {
            $loadUser = $userRepository->loadUserByIdentifier($userEmail);
            $usersOfClient = $userRepository->findBy(['customer' => $loadUser->getCustomer()->getId()]);
            return new JsonResponse(
                $serializer->serialize($usersOfClient, "json", SerializationContext::create()->setGroups(array('list'))),
                JsonResponse::HTTP_OK,
                [],
                true

            );

        }

        //dd($decodedJwtToken);
        //get email of the user

        //load User using email address
        // $loadUser = $userRepository->loadUserByIdentifier($userEmail);
        //check if the user is admin or not
        // if($loadUser == "ROLE_ADMIN"){

        // }
        //             $arrayRoles = $loadUser->getRoles();
        //             foreach ($arrayRoles as $role) {
        //                 if ($role == "ROLE_ADMIN") {

// //if the role is admin catch the user client id and get all the users belongs to the client

//                     $usersOfClient = $userRepository->findBy(['customer' => $loadUser->getCustomer()->getId()]);
        //                     return new JsonResponse(
        //                         $serializer->serialize($usersOfClient, "json", SerializationContext::create()->setGroups(array('list'))),
        //                         JsonResponse::HTTP_OK,
        //                         [],
        //                         true

//                     );

//                 } else {
        //                     return new JsonResponse('You are not the admin user', Response::HTTP_NOT_FOUND);
        //                 }

//             }

//             ;
        //         }

    }

    /**
     * @Route("/api/user/{id}", name="app_api_user_show",methods={"GET"})
     *
     */
    public function showUser(User $user, SerializerInterface $serializer, UserRepository $userRepository)
    {

        $decodedJwtToken = $this->decodeToken->loadUserInfo();

        $userEmail = $decodedJwtToken['email'];
        if ($this->decodeToken->userCan($userEmail, "ROLE_ADMIN")) {
            $loadUser = $userRepository->loadUserByIdentifier($userEmail);
            $adminClientId = $loadUser->getCustomer()->getId();
            // dd($adminClientId);

            $userClientId = $user->getCustomer()->getId();
            // dd($user->getCustomer()->getId());
            if ($adminClientId === $userClientId) {
                return new JsonResponse(
                    $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
                    JsonResponse::HTTP_OK,
                    [],
                    true

                );

            }

        }
        //decoding token
        // $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        // if ($decodedJwtToken) {

        //     //dd($decodedJwtToken);
        //     //get email of the user
        //     $userEmail = $decodedJwtToken['email'];

        //     //load User using email address
        //     $loadUser = $userRepository->loadUserByIdentifier($userEmail);
        //     //check if the user is admin or not
        //     // if($loadUser == "ROLE_ADMIN"){

        //     // }
        //     $arrayRoles = $loadUser->getRoles();

        //     foreach ($arrayRoles as $role) {

        //         if ($role == "ROLE_ADMIN") {
        //             //get admin client id
        //             $adminClientId = $loadUser->getCustomer()->getId();
        //             // dd($adminClientId);

        //             $userClientId = $user->getCustomer()->getId();
        //             // dd($user->getCustomer()->getId());
        //             if ($adminClientId === $userClientId) {
        //                 return new JsonResponse(
        //                     $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
        //                     JsonResponse::HTTP_OK,
        //                     [],
        //                     true

        //                 );

        //             } else {
        //                 return new JsonResponse('You are not the admin of this user', Response::HTTP_NOT_FOUND);
        //             }

        //         } else {
        //             return new JsonResponse('You are not the admin user', Response::HTTP_NOT_FOUND);
        //         }

        //     }

        //     ;
        // } else {
        //     return new JsonResponse('The token was not found or expired', Response::HTTP_NOT_FOUND);
        // }

    }

    /**
     * @Route("/api/user", name="app_api_user_create",methods={"POST"})
     * @Rest\View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function createUser(Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $urlGenarator,
        User $user,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        CustomerRepository $customerRepository
        // ConstraintViolationList $violations

    ) {
        $decodedJwtToken = $this->decodeToken->loadUserInfo();

        $userEmail = $decodedJwtToken['email'];
        if ($this->decodeToken->userCan($userEmail, "ROLE_ADMIN")) {
            $loadUser = $userRepository->loadUserByIdentifier($userEmail);
            //get admin client id
            $adminClientId = $loadUser->getCustomer()->getId();
            // set the client to the new user
            $user->setCustomer($customerRepository->findOneBy(['id' => $adminClientId]));
            $manager = $this->getDoctrine()->getManager();

            $plaintextPassword = $user->getPassword();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );

            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $manager->flush();

            return new JsonResponse(
                $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
                JsonResponse::HTTP_CREATED,
                ["Location" => $urlGenarator->generate("app_api_user_create", ["id" => $user->getId()])],
                true

            );

        }

        //decoding token
        // $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        // if ($decodedJwtToken) {

        //     //get email of the user
        //     $userEmail = $decodedJwtToken['email'];

        //     //load User using email address
        //     $loadUser = $userRepository->loadUserByIdentifier($userEmail);
        //     //check if the user is admin or not

        //     $arrayRoles = $loadUser->getRoles();

        //     foreach ($arrayRoles as $role) {

        //         if ($role == "ROLE_ADMIN") {
        //             //get admin client id
        //             $adminClientId = $loadUser->getCustomer()->getId();
        //             // set the client to the new user
        //             $user->setCustomer($customerRepository->findOneBy(['id' => $adminClientId]));
        //             $manager = $this->getDoctrine()->getManager();

        //             $plaintextPassword = $user->getPassword();

        //             $hashedPassword = $passwordHasher->hashPassword(
        //                 $user,
        //                 $plaintextPassword
        //             );

        //             $user->setPassword($hashedPassword);

        //             $manager->persist($user);
        //             $manager->flush();

        //             return new JsonResponse(
        //                 $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
        //                 JsonResponse::HTTP_CREATED,
        //                 ["Location" => $urlGenarator->generate("app_api_user_create", ["id" => $user->getId()])],
        //                 true

        //             );

        //         } else {
        //             return new JsonResponse('You are not the admin user to created a user', Response::HTTP_NOT_FOUND);
        //         }

        //     }

        //     ;
        // } else {
        //     return new JsonResponse('The token was not found or expired', Response::HTTP_NOT_FOUND);
        // }

        // $user->setCustomer($manager->getRepository(Customer::class)->findOneBy([]));

    }

    /**
     * @Route("/api/user/{id}", name="app_api_user_update",methods={"PUT"})
     * @Rest\View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function updateUser(User $user, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UserRepository $userRepository)
    {

        $decodedJwtToken = $this->decodeToken->loadUserInfo();

        $userEmail = $decodedJwtToken['email'];
        if ($this->decodeToken->userCan($userEmail, "ROLE_ADMIN")) {

            $object = $userRepository->findBy(["id" => $user->getId()]);
            // dd($object);
            $user = new DeserializationContext();
            $user->setAttribute('deserialization-constructor-target', $object);
            $serializer->deserialize(
                $request->getContent(),
                User::class, "json",
                $user
            );
            $manager->flush();

            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

        }
        //decoding token
        // $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        // if ($decodedJwtToken) {

        //     //get email of the user
        //     $userEmail = $decodedJwtToken['email'];

        //     //load User using email address
        //     $loadUser = $userRepository->loadUserByIdentifier($userEmail);
        //     //check if the user is admin or not

        //     $arrayRoles = $loadUser->getRoles();

        //     foreach ($arrayRoles as $role) {

        //         if ($role == "ROLE_ADMIN") {
        //             // dd($user);
        //             $object = $userRepository->findBy(["id" => $user->getId()]);
        //             // dd($object);
        //             $user = new DeserializationContext();
        //             $user->setAttribute('deserialization-constructor-target', $object);
        //             $serializer->deserialize(
        //                 $request->getContent(),
        //                 User::class, "json",
        //                 $user
        //             );
        //             $manager->flush();

        //             return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

        //         } else {
        //             return new JsonResponse('You are not the admin user to edit a user', Response::HTTP_NOT_FOUND);
        //         }

        //     }

        //     ;
        // } else {
        //     return new JsonResponse('The token was not found or expired', Response::HTTP_NOT_FOUND);
        // }

    }

    /**
     * @Route("/api/user/{id}", name="app_api_user_delete",methods={"DELETE"})
     * @Rest\View
     */
    public function delete(User $user, EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $decodedJwtToken = $this->decodeToken->loadUserInfo();

        $userEmail = $decodedJwtToken['email'];
        if ($this->decodeToken->userCan($userEmail, "ROLE_ADMIN")) {
            $loadUser = $userRepository->loadUserByIdentifier($userEmail);
            $adminClientId = $loadUser->getCustomer()->getId();
            // dd($adminClientId);

            $userClientId = $user->getCustomer()->getId();
            // dd($adminClientId, $userClientId);

            // dd($user->getCustomer()->getId());
            if ($adminClientId === $userClientId) {

                $manager->remove($user);
                $manager->flush();

                return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

            }

            // //decoding token
            // $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
            // // dd($decodedJwtToken);
            // if ($decodedJwtToken) {

            //     //get email of the user
            //     $userEmail = $decodedJwtToken['email'];

            //     //load User using email address
            //     $loadUser = $userRepository->loadUserByIdentifier($userEmail);
            //     //check if the user is admin or not

            //     $arrayRoles = $loadUser->getRoles();
            //     // dd($arrayRoles);
            //     foreach ($arrayRoles as $role) {
            //         // dd($role);
            //         if ($role == "ROLE_ADMIN") {
            //             $adminClientId = $loadUser->getCustomer()->getId();
            //             // dd($adminClientId);

            //             $userClientId = $user->getCustomer()->getId();
            //             // dd($adminClientId, $userClientId);

            //             // dd($user->getCustomer()->getId());
            //             if ($adminClientId === $userClientId) {

            //                 $manager->remove($user);
            //                 $manager->flush();

            //                 return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
            //             } else {
            //                 return new JsonResponse('You are not the autherized to realise this action', Response::HTTP_NOT_FOUND);
            //             }

            //         } else {
            //             return new JsonResponse('You are not the admin user to delete a user', Response::HTTP_NOT_FOUND);
            //         }

            //     }

            //     ;
            // } else {
            //     return new JsonResponse('The token was not found or expired', Response::HTTP_NOT_FOUND);
            // }

            // $manager->remove($user);
            // $manager->flush();

            // return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }

    }
}
