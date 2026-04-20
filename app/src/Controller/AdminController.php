<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
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
        if ( !$this->IsGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

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
        if ( !$this->IsGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        } 

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
    public function changeStatus(Item $item, OfferRepository $offerRepo, EntityManagerInterface $em, Request $request)
    {
        if ( !$this->IsGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }

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
                $this->addFlash('danger', 'L\'enchère possède des enchérisseur!!');
     
                return $this->redirectToRoute('app_admin_show', ['id' => $item->getId()]);
            }

        }
    }

    #[Route('/admin/show/close/{id<[0-9]+>}', name: 'app_admin_close')]
    public function close($id, Item $item, ItemRepository $itemRepo, OfferRepository $offerRepo, EntityManagerInterface $em, Request $request): Response
    {
        if ( !$this->IsGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }  

        $item = $itemRepo->findItemWithOffers($id, $offer=null);
        
        $submitedToken = $request->getPayload()->get('token');
        if ($this->IsCsrfTokenValid('close', $submitedToken)) {

            if (!empty($item->getOffers()) && $item->getStatus() !== 'closed') {
                $offer = $offerRepo->findWinner($item)[0]; 
                $item
                    ->setWinner($offer->getUser())
                    ->setStatus('closed')
                    ;
                $em->flush();
                $this->addFlash('success', 'L\'enchère est clôturé avec succès');
    
                return $this->redirectToRoute('app_admin_show', [
                    'id' => $id,
                    'item' => $item,
                ]);
            } 
        }

        return $this->redirectToRoute('app_admin_show', [
            'id' => $id,
            'item' => $item,
        ]);      
    }
}
