<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EncheresController extends AbstractController
{
    #[Route('/{id<[0-9]+>?null}', name: 'app_encheres')]
    public function index(?Category $category, ItemRepository $itemRepo, CategoryRepository $categoryRepo, EntityManagerInterface $em): Response
    {
        $id = $category ? (int) $category->getId() : null;
        $items = $itemRepo->findByCategory($id);

        return $this->render('encheres/index.html.twig', [
            'items' => $items,
            'categories' => $categoryRepo->findAll(),
        ]);
    }
 
    #[Route('/show/{id}', name: 'app_encheres_show')]
    public function show( Item $item,EntityManagerInterface $em): Response
    {
        return $this->render('encheres/show.html.twig', [
            'item' => $item,
        ]);
    }
}
