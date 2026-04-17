<?php

namespace App\Controller;

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
}
