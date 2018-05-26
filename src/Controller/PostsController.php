<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{
    public function list()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([], ['createdAt' => "DESC"]);

        return $this->render('posts/list.html.twig', ['posts' => $posts]);
    }

    public function show(Request $request, $id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if(!$post) {
            throw $this->createNotFoundException("Post " . $id . " not found");
        }

        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $comment->setCreatedAt(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', "Comment created");
        }

        return $this->render('posts/show.html.twig', ['post' => $post, 'form' => $commentForm->createView()]);
    }

    public function create(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $post->setAdmin($this->getUser());
            $post->setCreatedAt(new DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', "Post created!");

            return $this->redirectToRoute('dashboard_posts');
        }

        return $this->render('posts/create.html.twig', ['form' => $form->createView()]);
    }

    public function edit($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);

        if(!$post || $post->getAdmin()->getId() != $this->getUser()->getId()) {
            throw $this->createNotFoundException("Post " . $id . " not found.");
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Post updated!');

            return $this->redirectToRoute('post_show', ['id' => $id]);
        }

        return $this->render('posts/edit.html.twig', ['form' => $form->createView()]);
    }

    public function delete($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        if(!$post || $post->getAdmin()->getId() != $this->getUser()->getId()) {
            throw $this->createNotFoundException("Post " . $id . " not found!");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash("success", "Post deleted!");

        return $this->redirectToRoute("index");
    }
}
