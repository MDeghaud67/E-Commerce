<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiOrderController extends AbstractController
{
    #[Route('/api/order', name: 'app_api_order')]
    public function index(): Response
    {
        return $this->render('api_order/index.html.twig', [
            'controller_name' => 'ApiOrderController',
        ]);
    }

    /**
     * @Route("/api/orders", name="api_order", methods={"GET"})
     */
    public function listOrder(OrderRepository $orderRepository, SerializerInterface $serializer): Response
    {

        $orders = $orderRepository->findAll();

        $json = $serializer->serialize($orders, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['products']]);

        $response = new Response($json, 200, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/order/{id}", name="api_order_item", methods={"GET"})
     */
    public function findOneOrder(Order $order, SerializerInterface $serializer)
    {
        $json = $serializer->serialize($order, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['products']]);

        $response = new Response($json, 200, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }
}
