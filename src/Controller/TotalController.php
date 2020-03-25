<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Serie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TotalController extends AbstractController
{
    /**
     * @Route("/", name="total")
     */
    public function index()
    {
        $pdo = $this->getDoctrine()->getManager();
        $categories = $pdo->getRepository(Categorie::class)->findAll();
        $series = $pdo->getRepository(Serie::class)->findAll();

        return $this->render('total/index.html.twig', [
            'categories' => $categories,
            'series' => $series,
            ]);
    }

    public function compteur()
    {
        $qb = $this->createQueryBuilder('e');

        $qb ->select($qb->expr()->count('e'));

        return (int) $qb->getQuery()->getSingleScalarResult();
    }


}
