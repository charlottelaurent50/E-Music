<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Professeur;
use App\Entity\Cours;
use App\Form\ProfesseurType;

class ProfesseurController extends AbstractController
{
    #[Route('/professeur', name: 'app_professeur')]
    public function index(): Response
    {
        return $this->render('professeur/index.html.twig', [
            'controller_name' => 'ProfesseurController',
        ]);
    }

    public function consulterProfesseur(ManagerRegistry $doctrine, int $id){

        $repository = $doctrine->getRepository(Cours::class);
        $cours = $repository->findAll();

		$professeur= $doctrine->getRepository(Professeur::class)->find($id);

		if (!$professeur) {
			throw $this->createNotFoundException(
            'Aucun professeur trouvé avec le numéro '.$id
			);
		}

		return $this->render('professeur/consulter.html.twig', [
            'professeur' => $professeur,]);
	}

    public function listerProfesseur(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(Professeur::class);
    
    $professeurs= $repository->findAll();
    return $this->render('professeur/lister.html.twig', [
    'pProfesseurs' => $professeurs,]);	
    
    }

    public function ajouterProfesseur(ManagerRegistry $doctrine,Request $request){
    $professeur = new professeur();
	$form = $this->createForm(ProfesseurType::class, $professeur);
	$form->handleRequest($request);
 
	if ($form->isSubmitted() && $form->isValid()) {
 
            $professeur = $form->getData();
 
            $entityManager = $doctrine->getManager();
            $entityManager->persist($professeur);
            $entityManager->flush();
 
	    return $this->render('professeur/consulter.html.twig', ['professeur' => $professeur,]);
	}
	else
        {
            return $this->render('professeur/ajouter.html.twig', array('form' => $form->createView(),));
	    }
    }
}
