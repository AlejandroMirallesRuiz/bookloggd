<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/{bookId}', name: 'app_book')]
    public function book(int $bookId): Response
    {
        return $this->render('book/book.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/updateBook/{bookId}', methods: ['GET'],  name: 'app_updateBook')]
    public function updateBook(int $bookId): Response
    {
        return $this->render('book/updateBook.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    
    #[Route('/updateBook/{bookId}', methods: ['POST'],  name: 'post_updateBook')]
    public function post_UpdateBook(int $bookId): Response
    {
        return $this->render('book/updateBook.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/updateReadingStatus/{bookId}', methods: ['GET'], name: 'app_updateReadingStatus')]
    public function updateReadingStatus(int $bookId): Response
    {
        return $this->render('book/updateReadingStatus.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/updateReadingStatus/{bookId}', methods: ['POST'], name: 'post_updateReadingStatus')]
    public function post_UpdateReadingStatus(int $bookId): Response
    {
        return $this->render('book/updateReadingStatus.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
}
