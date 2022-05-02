<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiProductController extends AbstractController
{
    #[Route('/api/product', name: 'app_api_product')]
    public function index(): Response
    {
        return $this->render('api_product/index.html.twig', [
            'controller_name' => 'ApiProductController',
        ]);
    }
    /**
     * @Route("/api/products", name="api_product", methods={"GET"})
     */
    public function listProduct(ProductRepository $productRepository, Serializer $serializer): Response
    {

        $products = $productRepository->findAll();

        $json = $serializer->serialize($products, 'json');

        $response = new Response($json, 200, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/product/{id}", name="api_product_item", methods={"GET"})
     */
    public function findOneProduct(ProductRepository $productRepository, Product $product, SerializerInterface $serializer, int $id)
    {
        $product = $productRepository->find($id);

        $json = $serializer->serialize($product, 'json');

        $response = new Response($json, 200, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/products", name="api_product_add", methods={"POST"})
     */
    public function addProduct(Request $request, Serializer $serializer, EntityManager $em, ManagerRegistry $doctrine)
    {
        $data = $request->getContent();
        $product = new Product;
        $product = $serializer->deserialize($data, Product::class, 'json');

        $em = $doctrine->getManager();
        $em->persist($product);
        $em->flush();

        $json = $serializer->serialize($product, 'json');

        $response = new Response($json, 201, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }

    /**
     * @Route("/api/product/{id}", name="api_product_put", methods={"PUT"})
     */
    public function modify(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, Product $product, ProductRepository $productRepository, int $id, ManagerRegistry $doctrine)
    {
        $data = $request->getContent();

        

        //$form = $this->createForm(Product::class, $product);
        //$form->submit($data);

        
        
        if(!$product){
            $product = new Product();
            $json = $serializer->serialize($product, 'json');
            $response = new Response($json, 201, [
                "content-Type" => "application/json"
            ]);
        }
        else{
            $product = $productRepository->find($id);
            $product = $serializer->deserialize($data, Product::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $product]);

            $em = $doctrine->getManager();
            $em->persist($product);
            $em->flush();

            $json = $serializer->serialize($product, 'json');
            $response = new Response($json, 200, [
                "content-Type" => "application/json"
            ]);
        }
        return $response;
    }

    /**
     * @Route("/api/product/{id}", name="api_product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, Product $product, ProductRepository $productRepository, int $id)
    {
        $product = $productRepository->find($id);

        $entityManager->remove($product);
        $entityManager->flush();

        $response = new Response(null, 204, [
            "content-Type" => "application/json"
        ]);

        return $response;
    }
}
