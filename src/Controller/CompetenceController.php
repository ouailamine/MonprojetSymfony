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
use App\Entity\Competence;

class CompetenceController extends AbstractController
{
  //liste competences
    /**
     * @Route("/Competences", name="Conpetenc")
     * @Method ({"GET"})
     */
    public function Competences()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Competences = $this->getDoctrine()->getRepository(Competence::class)->findAll();
        return $this->render('competence/Competences.html.twig', array('Competences' => $Competences));
            
    }
    //session admin competence
     /**  
     * @Route("/admincompetence", name="competencemodif")
     * @Method ({"GET"})
     */


    public function competencemodif()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Competences = $this->getDoctrine()->getRepository(Competence::class)->findAll();
        return $this->render('competence/admincompetence.html.twig', array('Competences' => $Competences));
            
    }

    //creation new competence
     /**
     * @Route("/newComp", name="ajouter")
     * @Method({"GET"})
     */
    public function newCompetence(Request $request)
    {
       $entityManager = $this->getDoctrine()->getManager();
       $competence = new Competence();
       $form = $this->createFormBuilder($competence)
            
            ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();
        
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $competence = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competence);
            $entityManager->flush();
            return $this->redirectToRoute('competencemodif');
        }
        return $this->render('competence/newcompetence.html.twig', array('form' => $form->createView()));
    }
    
//modifier et save  un competence

/**
 * @Route("/competence/edit/{id}")
 */
public function update(Request $request, $id)
{
    $competence = $this->getDoctrine()->getRepository(Competence::class)->find($id);
    $form = $this->createFormBuilder($competence)
        ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('competencemodif');
    }
    return $this->render('competence/editcompetence.html.twig', array('form' => $form->createView()));
}

//supprimer un competence
/**
 * @Route("/competence/delete/{id}")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $competence = $entityManager->getRepository(Competence::class)->find($id);

    if (!$competence) {
        throw $this->createNotFoundException(
            'No competence found for id '.$id
        );
    }

    $entityManager->remove($competence);
    $entityManager->flush();
  
    
    return $this->redirectToRoute('competencemodif');
}  
}
