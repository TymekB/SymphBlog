<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Category\CategoryCreator;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var CategoryCreator
     */
    private $categoryCreator;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, PostRepository $postRepository, CategoryRepository $categoryRepository, CategoryCreator $categoryCreator)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
        $this->categoryCreator = $categoryCreator;
        $this->categoryRepository = $categoryRepository;
    }

    public function list()
    {
        $posts = $this->postRepository->findBy([], ['createdAt' => "DESC"]);

        return $this->render('posts/list.html.twig', ['posts' => $posts]);
    }

    /**
     * @param Category $category
     * @return Response
     * @ParamConverter()
     */
    public function listCategory(Category $category = null)
    {
        if(empty($category)) {
            throw $this->createNotFoundException("Category not found");
        }

        $posts = $category->getPosts();

        return $this->render('posts/list.html.twig', ['posts' => $posts]);
    }

    public function show(Request $request, $id)
    {
        $post = $this->postRepository->find($id);

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

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

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
            $this->entityManager->persist($post);

            $categories = $form->get('categories')->getData();
            $this->categoryCreator->create($post, $categories);

            $post->setAdmin($this->getUser());
            $post->setCreatedAt(new DateTime());

            $this->entityManager->flush();

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
            $categories = $form->get('categories')->getData();
            $this->categoryCreator->create($post, $categories);

            $entityManager->flush();

            $this->addFlash('success', 'Post updated!');

            return $this->redirectToRoute('post_show', ['id' => $id]);
        }

        return $this->render('posts/edit.html.twig', ['form' => $form->createView()]);
    }

    public function delete($id)
    {
        $post = $this->postRepository->find($id);

        if(!$post || $post->getAdmin()->getId() != $this->getUser()->getId()) {
            throw $this->createNotFoundException("Post " . $id . " not found!");
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        $this->addFlash("success", "Post deleted!");

        return $this->redirectToRoute("index");
    }
}
