<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", name="post.")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"get"})
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig');
    }

    /**
     * @Route("/create", name="create", methods={"get"})
     */
    public function create()
    {
        return $this->render('post/create.html.twig');
    }
}
