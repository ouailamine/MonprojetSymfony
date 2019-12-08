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
use App\Entity\Experience;

class ExperienceController extends AbstractController
{
    //liste experiences

    /**
     * @Route("/Experiences", name="experienc")
     * @Method ({"GET"})
     */
    public function Experience()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Experiences = $this->getDoctrine()->getRepository(Experience::class)->findAll();
        return $this->render('experience/Experiences.html.twig', array('Experiences' => $Experiences));
            
    }
    //session admin experience
     /**  
     * @Route("/adminexperience", name="experiencemodif")
     * @Method ({"GET"})
     */
    public function experiencemodif()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $experiences = $this->getDoctrine()->getRepository(experience::class)->findAll();
        return $this->render('experience/adminexperience.html.twig', array('experiences' =>$experiences));
            
    }

    //creation new $experiences
     /**
     * @Route("/experience/new", name="ajoute")
     * @Method({"GET","POST"})
     */
    public function createexperience(Request $request)
    {
       $entityManager = $this->getDoctrine()->getManager();
       $experience = new experience();
       $form = $this->createFormBuilder($experience)
            
       ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
       ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
       ->add('date', TextType::class, array('attr' => array('class' => 'form-control')))
       ->add('save', SubmitType::class, array('label' => 'new', 'attr' => array('class' => 'btn btn-primary mt-3')))
       ->getForm();
        
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $experience = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($experience);
            $entityManager->flush();
            return $this->redirectToRoute('experiencemodif');
        }
        return $this->render('experience/newexperience.html.twig', array('form' => $form->createView()));
    }
    
//modifier et save  un experience

/**
 * @Route("/experience/edit/{id}")
 */
public function update(Request $request, $id)
{
    $experience = $this->getDoctrine()->getRepository(experience::class)->find($id);
    $form = $this->createFormBuilder($experience)
    ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
    ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
    ->add('date', TextType::class, array('attr' => array('class' => 'form-control')))
    ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
    ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('experiencemodif');
    }
    return $this->render('experience/editexperience.html.twig', array('form' => $form->createView()));
}

//supprimer un experience
/**
 * @Route("/experience/delete/{id}")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $experience = $entityManager->getRepository(experience::class)->find($id);

    if (!$experience) {
        throw $this->createNotFoundException(
            'No experience found for id '.$id
        );
    }

    $entityManager->remove($experience);
    $entityManager->flush();
  
    
    return $this->redirectToRoute('experiencemodif');
}  
}
