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
use App\Entity\Contact;

class ContactController extends AbstractController
{
   //contact
    /**
     * @Route("/contact", name="contactermoi")
     * @Method ({"GET"})
     */
    public function contactermoi()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $contacts = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        return $this->render('contact/contact.html.twig', array('contacts' => $contacts));
            
    }

    //session admin contact
     /**  
     * @Route("/admincontact", name="contactmodif")
     * @Method ({"GET"})
     */
    public function contactmodif()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $contacts = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        return $this->render('contact/admincontact.html.twig', array('contacts' =>$contacts));
            
    }

    //creation new $contacts
     /**
     * @Route("/contact/new", name="ajou")
     * @Method({"GET","POST"})
     */
    public function createcontact(Request $request)
    {
       $entityManager = $this->getDoctrine()->getManager();
       $contact = new contact();
       $form = $this->createFormBuilder($contact)
            
            ->add('adresse', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('telephone', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
            ->add('mail', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-2')))
            ->getForm();
        
            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            return $this->redirectToRoute('contactmodif');
        }
        return $this->render('contact/newcontact.html.twig', array('form' => $form->createView()));
    }
    
//modifier et save  un contact

/**
 * @Route("/contact/edit/{id}")
 */
public function update(Request $request, $id)
{
    $contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);
    $form = $this->createFormBuilder($contact)
    ->add('adresse', TextType::class, array('attr' => array('class' => 'form-control')))
    ->add('telephone', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
    ->add('mail', TextType::class, array('attr' => array('class' => 'form-control')))
    ->add('edit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-2')))
    ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('contactmodif');
    }
    return $this->render('contact/editcontact.html.twig', array('form' => $form->createView()));
}

//supprimer un contact
/**
 * @Route("/contact/delete/{id}")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $contact = $entityManager->getRepository(Contact::class)->find($id);

    if (!$contact) {
        throw $this->createNotFoundException(
            'No contact found for id '.$id
        );
    }

    $entityManager->remove($contact);
    $entityManager->flush();
  
    
    return $this->redirectToRoute('contactmodif');
}  
}
