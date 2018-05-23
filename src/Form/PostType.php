<?php
/**
 * Created by PhpStorm.
 * User: tymek
 * Date: 04.05.18
 * Time: 13:59
 */

namespace App\Form;

use App\Form\DataTransformer\TextToCleanHtmlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    /**
     * @var TextToCleanHtmlTransformer
     */
    private $textToCleanHtmlTransformer;

    public function __construct(TextToCleanHtmlTransformer $textToCleanHtmlTransformer)
    {
        $this->textToCleanHtmlTransformer = $textToCleanHtmlTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header', TextType::class)
            ->add('body', TextareaType::Class, ['attr' => ['id' => 'article-ckeditor']]);

        $builder->get('body')->addModelTransformer($this->textToCleanHtmlTransformer);
    }
}
