<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EncheresController extends AbstractController
{
    #[Route('/', name: 'app_encheres')]
    public function index( ItemRepository $itemRepo,EntityManagerInterface $em): Response
    {
        return $this->render('encheres/index.html.twig', [
            'items' => $itemRepo->findAll(),
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
