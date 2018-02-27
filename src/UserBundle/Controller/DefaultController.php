<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Front/Default/index.html.twig');
    }
    public function MedecinRegisterAction()
    {
        return $this->render('@User/Medecin/formmedecin.html.twig');
    }
}
