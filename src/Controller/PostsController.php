<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostsController extends Controller
{
    public function list()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('posts/list.html.twig', ['posts' => $posts]);
    }
}
