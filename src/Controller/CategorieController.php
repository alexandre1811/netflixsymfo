<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();
        $categorie=new Categorie();
        $form= $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pdo->persist($categorie);
            $pdo->flush();
        }

        $categories = $pdo->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'form_ajout'=> $form->createView(),
        ]);

    }

    /**
     * @Route("/categorie/{id}", name="modifiercategorie")
     */

    public function modifiercategorie(Categorie $categorie, Request $request)
    {

        if ($categorie != null) {
            $form = $this->createForm(CategorieType::class, $categorie);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($categorie);
                $pdo->flush();
                $this->addFlash("success", "Categorie Modifiée");
            }

            return $this->render('categorie/categorie.html.twig', [
                'categorie' => $categorie,
                'form_edit' => $form->createView()

            ]);
        }

        else{
            $this->addFlash("danger", "Categorie Introuvable");
        }
    }

    /**
     *@Route("categorie/delete/{id}", name="suppressioncategorie")
     */

    public function suppressioncategorie(Categorie $categorie=null){
        if ($categorie !=null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($categorie);
            $pdo->flush();
            $this->addFlash("success", "Categorie supprimée");
        }

        else{
            $this->addFlash("danger", "Categorie intouvable");
        }

        return $this->redirectToRoute('categorie');

    }


}
