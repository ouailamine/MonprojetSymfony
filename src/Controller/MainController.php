<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Competence;
use App\Entity\Contact;
use App\Entity\Experience;
use App\Entity\Formation;
use App\Entity\Hobbie;
use App\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home()
    {return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',]);}


    /**
     * @Route("/home", name="accueile")
     */
    public function accueil()
    {
        return $this->render('session/accueil.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }



     /**
     * @Route("/hobbies", name="hobbi")
     * @Method ({"GET"})
     */
    public function hobbies()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $hobbies = $this->getDoctrine()->getRepository(Hobbie::class)->findAll();
        return $this->render('session/hobbies.html.twig', array('hobbies' => $hobbies));
            
    }

    /**
     * @Route("/Formation", name="format")
     * @Method ({"GET"})
     */
    public function formations()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        return $this->render('session/Formation.html.twig', array('formations' => $formations));
            
    }

    /**
     * @Route("/Experiences", name="experienc")
     * @Method ({"GET"})
     */
    public function Experience()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Experiences = $this->getDoctrine()->getRepository(Experience::class)->findAll();
        return $this->render('session/Experiences.html.twig', array('Experiences' => $Experiences));
            
    }

    /**
     * @Route("/contact", name="contactermoi")
     * @Method ({"GET"})
     */
    public function contactermoi()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $contacts = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        return $this->render('session/contact.html.twig', array('contacts' => $contacts));
            
    }

    /**
     * @Route("/Competences", name="Conpetenc")
     * @Method ({"GET"})
     */
    public function Competences()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Competences = $this->getDoctrine()->getRepository(Competence::class)->findAll();
        return $this->render('session/Competences.html.twig', array('Competences' => $Competences));
            
    }

    /**
     * @Route("/mesprojet", name="projet")
     * @Method ({"GET"})
     */
    public function projet()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        return $this->render('session/mesprojet.html.twig', array('projets' => $projets));
            
    }


    /**
     * @Route("/connexion", name="login")
     */
    public function login ()
    {return $this->render('security/login.html.twig', [
            'controller_name' => 'MainController',]);}
     
     /**  
     * @Route("/adminprojet", name="projetmodif")
     * @Method ({"GET"})
     */
    public function projetmodif()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        return $this->render('sessionAdmin/projet/adminprojet.html.twig', array('projets' => $projets));
            
    }

    /**
     * @Route("/admin", name="administrateur")
     */
    public function administrateur()
    {return $this->render('sessionAdmin/admin.html.twig', [
            'controller_name' => 'MainController',]);}
    
    
    

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
        return $this->render('sessionAdmin/projet/newprojet.html.twig', array('form' => $form->createView()));
    }
    


/**
 * @Route("/projet/edit/{id}")
 */
public function update(Request $request, $id)
{
    $projet = $this->getDoctrine()->getRepository(Projet::class)->find($id);
    $form = $this->createFormBuilder($projet)
        ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('description', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('projetmodif');
    }
    return $this->render('sessionAdmin/projet/edit.html.twig', array('form' => $form->createView()));
}

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