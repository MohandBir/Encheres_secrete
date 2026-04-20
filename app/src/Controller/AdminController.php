<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin/{id<[0-9]+>?null}', name: 'app_admin_index')]
    public function index(?Category $category, ItemRepository $itemRepo, CategoryRepository $categoryRepo, EntityManagerInterface $em): Response
    {
        $id = $category ? (int) $category->getId() : null;
        $items = $itemRepo->findByCategory($id);

        return $this->render('admin/index.html.twig', [
            'items' => $items,
            'categories' => $categoryRepo->findAll(),
        ]);
    }
}
