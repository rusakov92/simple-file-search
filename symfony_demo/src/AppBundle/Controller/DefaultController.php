<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) : Response
    {
//        dump($request->request->all());die;

        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request) : Response
    {
        dump($request->request->all());die;
    }
}
