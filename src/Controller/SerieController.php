<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Serie;
use App\Form\ProduitType;
use App\Form\SerieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SerieController extends AbstractController
{
    /**
     * @Route("/serie", name="serie")
     */
    public function index(Request $request)
    {
        $pdo =$this->getDoctrine()->getManager();
        $serie=new Serie();
        $form = $this->createForm(SerieType::class, $serie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $fichier = $form->get('affiche')->getData();
            if ($fichier){
                $nomFichier= uniqid().'.'.$fichier->guessExtension();
                try {
                    $fichier->move(
                        $this->getParameter('upload_dir'),
                        $nomFichier
                    );
                }
                catch (FileException $e){
                    $this->addFlash('danger', "Impossiple d'uploader le fichier");
                    return $this->redirectToRoute('serie');
                }
                $serie->setAffiche($nomFichier);
            }
            $pdo->persist($serie);
            $pdo->flush();
        }

        $series = $pdo->getRepository(Serie::class)->findAll();

        return $this->render('serie/index.html.twig', [
            'series' => $series,
            'form_ajout' => $form->createView(),
        ]);
    }



    /**
     * @Route("serie/modification/{id}", name="modifuneserie")
     */

    public function modifierserie(Serie $serie=null, Request $request){
        if ($serie !=null){
            $form= $this->createForm(SerieType::class, $serie);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $pdo = $this->getDoctrine()->getManager();
                $fichier = $form->get('affiche')->getData();
                if ($fichier){
                    $nomFichier= uniqid().'.'.$fichier->guessExtension();
                    try {
                        $fichier->move(
                            $this->getParameter('upload_dir'),
                            $nomFichier
                        );
                    }
                    catch (FileException $e){
                        $this->addFlash('danger', "Impossiple d'uploader le fichier");
                        return $this->redirectToRoute('serie');
                    }
                    $serie->setAffiche($nomFichier);
                }
                $pdo->persist($serie);
                $pdo->flush();
                $this->addFlash("success", "Serie Modifié");

            }

            return $this->render('serie/serie.html.twig', [
                'serie' => $serie,
                'form_edit'=>$form -> createView()

            ]);
        }
        else{
            return $this->redirectToRoute('serie');
        }
    }

    /**
     *@Route("serie/suppression/{id}", name="suppressionserie")
     */

    public function suppressionserie(Serie $serie=null){
        if ($serie !=null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($serie);
            $pdo->flush();
            $this->addFlash("success", "Serie supprimée");
        }
        else{
            $this->addFlash("danger", "Serie intouvable");
        }

        return $this->redirectToRoute('serie');

    }
}
