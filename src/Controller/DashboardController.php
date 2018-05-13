<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DashboardController extends Controller
{
    public function posts()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        return $this->render('dashboard/posts.html.twig', ['posts' => $posts]);
    }

    public function comments(AuthorizationCheckerInterface $authChecker)
    {
        if($authChecker->isGranted("ROLE_ADMIN")) {
            $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        } else {
            $comments = $this->getUser()->getComments();
        }

        return $this->render('dashboard/comments.html.twig', ['comments' => $comments]);
    }
}
