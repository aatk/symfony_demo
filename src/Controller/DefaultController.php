<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('index.html.twig', []);
    }
}