<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function about()
    {
        return $this->render('home/about.html.twig');
    }

    public function services()
    {
        return $this->render('home/services.html.twig');
    }

    public function contact()
    {
        return $this->render('home/contact.html.twig');
    }
}
