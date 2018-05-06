<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CommentsController extends Controller
{
    public function create(Request $request, AuthorizationCheckerInterface $authChecker, $postId)
    {
        $comment = new Comment();
        $post = $this->getDoctrine()->getRepository(Post::class)->find($postId);

        if(!$post) {
            throw $this->createNotFoundException("Post " . $postId . " not found!");
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if(!$authChecker->isGranted("IS_AUTHENTICATED_FULLY")) {
                $this->addFlash('danger', 'You must be logged to add a comment!');
            } else {

                $comment->setCreatedAt(new \DateTime());
                $comment->setUser($this->getUser());
                $comment->setPost($post);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($comment);
                $entityManager->flush();
            }

            return $this->redirectToRoute("post_show", ['id' => $post->getId()]);
        }

        return $this->render('comments/create.html.twig', ['form' => $form->createView(), 'post' => $post]);
    }
}
