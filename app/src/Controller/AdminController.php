<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\Offer;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin/{id<[0-9]+>?null}', name: 'app_admin_index')]
    public function index(?Category $category, ItemRepository $itemRepo, CategoryRepository $categoryRepo): Response
    {
        $id = $category ? (int) $category->getId() : null;
        $items = $itemRepo->findByCategoryWithOffers($id);
        return $this->render('admin/index.html.twig', [
            'items' => $items,
            'categories' => $categoryRepo->findAll(),
        ]);
    }

    #[Route('/admin/show/{id<[0-9]+>}', name: 'app_admin_show')]
    public function show($id, ItemRepository $itemRepo, OfferRepository $offerRepo): Response
    {
        $item = $itemRepo->find($id);
        $offer = $offerRepo->findOneBy(['item' => $item]);
        if ($offer) {
            $item = $itemRepo->findItemWithOffers($id, $offer = null);
        }
        return $this->render('admin/show.html.twig', [
            'item' => $item ,
        ]);

    } 

    #[Route('/admin/change-status/{id}', name: 'app_admin_change_status')]
    public function changeStatus(Item $item, OfferRepository $offerRepo, EntityManagerInterface $em, Request $request): Response
    {
        $offer = $offerRepo->findOneBy(['item' => $item]);

        $submitedToken = $request->getPayload()->get('token');
        if($this->isCsrfTokenValid('change', $submitedToken)) {
            if ($item->getStatus() === 'unpublished') {
                $item->setStatus('published');
                $em->flush();
                $this->addFlash('success', 'L\'enchère est publié avec succès');
     
                return $this->redirectToRoute('app_admin_index');
            }
            if ($item->getStatus() === 'published' && !$offer) {
                $item->setStatus('unpublished');
                $em->flush();
                $this->addFlash('success', 'L\'enchère remise à Non publiée');
     
                return $this->redirectToRoute('app_admin_index');
            } else {
                $this->addFlash('danger', 'L\'enchère possède des enréchisseur!!');
     
                return $this->redirectToRoute('app_admin_show', ['id' => $item->getId()]);
            }

        }
    }
}
