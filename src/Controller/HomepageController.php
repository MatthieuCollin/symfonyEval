<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function viewHomepage(EntityManagerInterface $entityManager): Response
    {

        $repoProduits = $entityManager->getRepository(Produit::class);
        $produits = $repoProduits->findAll();

        $size = count($produits);

        $i = $size - 5;

        $data = [];

        while($i < $size){

            $data[] = [
                'id' => $produits[$i]->getId(),
                'nom' => $produits[$i]->getNom(),
                'img' => $produits[$i]->getImage()
            ];

            $i++;
        };


        return $this->render('homepage/index.html.twig', [
            'data' => $data,
        ]);
    }
}
