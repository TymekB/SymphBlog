<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentsController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, CommentRepository $commentRepository)
    {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }


    public function edit($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);

        if(!$comment || $comment->getUser()->getId() != $this->getUser()->getId()) {
            throw $this->createNotFoundException("Comment " . $id . " not found!");
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("success", "Comment edited!");

            return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
        }

        return $this->render('comments/edit.html.twig', ['form' => $form->createView()]);
    }

    public function delete($id)
    {
        $comment = $this->commentRepository->find($id);

        if(!$comment || $comment->getUser()->getId() != $this->getUser()->getId()) {
            throw $this->createNotFoundException("Comment " . $id . " not found!");
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        $this->addFlash("success", "Comment deleted!");

        return $this->redirectToRoute("post_show", ['id' => $comment->getPost()->getId()]);
    }
}
