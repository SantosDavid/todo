<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/lists", name="list.")
 */

class ListController extends Controller
{
    /**
     * @Route("/index", name="index")
     */
    public function indexAction()
    {
        return $this->render('lists/index.html.twig');
    }
}
