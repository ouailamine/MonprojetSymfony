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
use App\Entity\Formation;

class FormationController extends AbstractController
{
   //liste formations
    /**
     * @Route("/Formation", name="format")
     * @Method ({"GET"})
     */
    public function formations()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        return $this->render('formation/Formation.html.twig', array('formations' => $formations));
            
    }
    //session admin formation
     /**  
     * @Route("/adminformation", name="formationmodif")
     * @Method ({"GET"})
     */
    public function formationmodif()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formations = $this->getDoctrine()->getRepository(formation::class)->findAll();
        return $this->render('formation/adminformation.html.twig', array('formations' =>$formations));
            
    }

    //creation new $formations
     /**
     * @Route("/formation/new", name="ajout")
     * @Method({"GET","POST"})
     */
    public function createformation(Request $request)
    {
       $entityManager = $this->getDoctrine()->getManager();
       $formation = new formation();
       $form = $this->createFormBuilder($formation)
            
       ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
       
       ->add('date', TextType::class, array('attr' => array('class' => 'form-control')))
       ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('formationmodif');
        }
        return $this->render('formation/newformation.html.twig', array('form' => $form->createView()));
    }
    
//modifier et save  un formation

/**
 * @Route("/formation/edit/{id}")
 */
public function update(Request $request, $id)
{
    $formation = $this->getDoctrine()->getRepository(formation::class)->find($id);
    $form = $this->createFormBuilder($formation)
    ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
   
    ->add('date', TextType::class, array('attr' => array('class' => 'form-control')))
    ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('formationmodif');
    }
    return $this->render('formation/editformation.html.twig', array('form' => $form->createView()));
}

//supprimer un formation
/**
 * @Route("/formation/delete/{id}")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $formation = $entityManager->getRepository(formation::class)->find($id);

    if (!$formation) {
        throw $this->createNotFoundException(
            'No formation found for id '.$id
        );
    }

    $entityManager->remove($formation);
    $entityManager->flush();
  
    
    return $this->redirectToRoute('formationmodif');
}  
}
