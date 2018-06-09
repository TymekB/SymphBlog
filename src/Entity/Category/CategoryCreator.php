<?php

namespace App\Entity\Category;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Post $post
     * @param Category[] $categories
     */
    public function create(Post $post, $categories)
    {
        foreach($categories as $category) {
            $post->addCategory($category);
        }
    }
}