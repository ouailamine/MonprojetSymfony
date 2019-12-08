<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 //import entity
use App\Entity\Projet;

class ProjetController extends AbstractController
{
   //liste mes projets
    /**
     * @Route("/mesprojet", name="projet")
     * @Method ({"GET"})
     */
    public function projet()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        return $this->render('projet/mesprojet.html.twig', array('projets' => $projets));
            
    }
    //session admin projet
     /**  
     * @Route("/adminprojet", name="projetmodif")
     * @Method ({"GET"})
     */
    public function projetmodif()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        return $this->render('projet/adminprojet.html.twig', array('projets' => $projets));
            
    }

    //creation new projet
     /**
     * @Route("/projet/new", name="new")
     * @Method({"GET","POST"})
     */
    public function createProjet(Request $request)
    {
       $entityManager = $this->getDoctrine()->getManager();
       $projet = new Projet();
       $form = $this->createFormBuilder($projet)
            
            ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
            ->add('lien', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();
        
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();
            return $this->redirectToRoute('projetmodif');
        }
        return $this->render('projet/newprojet.html.twig', array('form' => $form->createView()));
    }
    
//modifier et save  un projet

/**
 * @Route("/projet/edit/{id}")
 */
public function update(Request $request, $id)
{
    $projet = $this->getDoctrine()->getRepository(Projet::class)->find($id);
    $form = $this->createFormBuilder($projet)
        ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
        ->add('lien', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('projetmodif');
    }
    return $this->render('projet/edit.html.twig', array('form' => $form->createView()));
}

//supprimer un projet
/**
 * @Route("/projet/delete/{id}")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $projet = $entityManager->getRepository(Projet::class)->find($id);

    if (!$projet) {
        throw $this->createNotFoundException(
            'No projet found for id '.$id
        );
    }

    $entityManager->remove($projet);
    $entityManager->flush();
  
    
    return $this->redirectToRoute('projetmodif');
}
}
