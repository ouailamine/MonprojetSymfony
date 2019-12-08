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
     * @Route("/", name="accueile")
     */
    public function accueil()
    {
        return $this->render('main/accueil.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/connexion", name="login")
     */
    public function login ()
    {return $this->render('security/login.html.twig', [
            'controller_name' => 'MainController',]);}
     

    /**
     * @Route("/admin", name="administrateur")
     */
    public function administrateur()
    {return $this->render('main/admin.html.twig', [
            'controller_name' => 'MainController',]);}
    

}