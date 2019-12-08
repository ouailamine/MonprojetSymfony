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
use App\Entity\Hobbie;


class HobbieController extends AbstractController
{
    //liste des hobbies
     /**
     * @Route("/hobbies", name="hobbi")
     * @Method ({"GET"})
     */
    public function hobbies()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $hobbies = $this->getDoctrine()->getRepository(Hobbie::class)->findAll();
        return $this->render('hobbie/hobbies.html.twig', array('hobbies' => $hobbies));
            
    }
    //session admin hobbie
     /**  
     * @Route("/adminhobbie", name="hobbiemodif")
     * @Method ({"GET"})
     */
    public function hobbiemodif()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $hobbies = $this->getDoctrine()->getRepository(Hobbie::class)->findAll();
        return $this->render('hobbie/adminhobbie.html.twig', array('hobbies' =>$hobbies));
            
    }

    //creation new $hobbies
     /**
     * @Route("/hobbie/new", name="ajout")
     * @Method({"GET","POST"})
     */
    public function createhobbie(Request $request)
    {
       $entityManager = $this->getDoctrine()->getManager();
       $hobbie = new hobbie();
       $form = $this->createFormBuilder($hobbie)
            
            
            ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
           
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();
        
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $chobbie = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hobbie);
            $entityManager->flush();
            return $this->redirectToRoute('hobbiemodif');
        }
        return $this->render('hobbie/newhobbie.html.twig', array('form' => $form->createView()));
    }
    
//modifier et save  un hobbie

/**
 * @Route("/hobbie/edit/{id}")
 */
public function update(Request $request, $id)
{
    $hobbie = $this->getDoctrine()->getRepository(hobbie::class)->find($id);
    $form = $this->createFormBuilder($hobbie)
        
        ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('hobbiemodif');
    }
    return $this->render('hobbie/edithobbie.html.twig', array('form' => $form->createView()));
}

//supprimer un hobbie
/**
 * @Route("/hobbie/delete/{id}")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $hobbie = $entityManager->getRepository(Hobbie::class)->find($id);

    if (!$hobbie) {
        throw $this->createNotFoundException(
            'No hobbie found for id '.$id
        );
    }

    $entityManager->remove($hobbie);
    $entityManager->flush();
  
    
    return $this->redirectToRoute('hobbiemodif');
}  
}
