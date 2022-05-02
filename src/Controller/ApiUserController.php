<?php

namespace App\Controller;

use App\Entity\User; 
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiUserController extends AbstractController
{
    #[Route('/api/user', name: 'app_api_user')]
    public function index(): Response
    {
        return $this->render('api_user/index.html.twig', [
            'controller_name' => 'ApiUserController',
        ]);
    }

    #[Route('/api/users', name: "api_user_disp", methods: "GET")]
    public function dispUsers(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $users = $userRepository->findAll();

        $json = $serializer->serialize($users, 'json');

        //dd($normalized);

        //$json = json_encode($normalized);

        $response = new Response($json, 200, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }
    
    #[Route("/api/users/{id}", name: "api_user_put", methods: "PUT")]
    public function updateUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, User $user, UserRepository $userRepository, int $id, ManagerRegistry $doctrine)
    {
        $data = $request->getContent();

        

        //$form = $this->createForm(Product::class, $product);
        //$form->submit($data);

        
        
        if(!$user){
            $user = new User();
            $json = $serializer->serialize($user, 'json');
            $response = new Response($json, 201, [
                "content-Type" => "application/json"
            ]);
        }
        else{
            $user = $userRepository->find($id);
            $user = $serializer->deserialize($data, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $json = $serializer->serialize($user, 'json');
            $response = new Response($json, 200, [
                "content-Type" => "application/json"
            ]);
        }
        return $response;
    }

    #[Route("/api/users/{id}", name: "api_user_delete", methods: "DELETE")]
    public function deleteUser(Request $request, EntityManagerInterface $entityManager, User $user, UserRepository $userRepository, int $id)
    {
        $user = $userRepository->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        $response = new Response(null, 204, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }
}
