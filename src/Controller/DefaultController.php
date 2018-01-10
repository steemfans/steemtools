<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, SessionInterface $session)
    {
        if ($session->get('login_status') == true) {
            return $this->redirectToRoute('user_setting');
        }
        return $this->render('default/index.html.twig', array(
            'title' => 'Steem Mention',
            'login_status' => $session->get('login_status'),
        ));
    }
}
