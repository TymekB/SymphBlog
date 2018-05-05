<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    public function list()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        return $this->render('posts/list.html.twig', ['posts' => $posts]);
    }

    public function create(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(UserType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post->setAdmin($this->getUser());
            $post->setCreatedAt(new \DateTime('today'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', "Post created!");
        }

        return $this->render('posts/create.html.twig', ['form' => $form->createView()]);
    }
}
