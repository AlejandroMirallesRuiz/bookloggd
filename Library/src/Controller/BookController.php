<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityManagerInterface;

use DateTime;

use App\Repository\LibroRepository;
use App\Repository\LanguageRepository;

use App\Entity\Libro;
use App\Entity\Language;
use App\Entity\Lectura;

class BookController extends AbstractController
{

    private function saveImageTemporalFile($image, $fileName = "temporal"){
        $imagesFolder = $this->getParameter('kernel.project_dir').'/public/image/';

        $fileName = $fileName . ".jpg";

        file_put_contents($imagesFolder . $fileName, $image);

        return $fileName;
    }
    

    
    #[Route('/', name: 'app_index')]
    public function index(LibroRepository $libroRepository, Request $request): Response{
        # Get all books
        $books = $libroRepository->findAllSorted();

        $images = [];

        # Save all book images
        foreach ($books as $book){
            $image = $book->getPortada();
            $book_id = $book->getId();

            $imageFile = $this->saveImageTemporalFile($image, $book_id );
            
            $images[] = $imageFile;
        }
        

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
            'images' => $images
        ]);
    }

    #[Route('/book/{bookId}', name: 'app_book')]
    public function book(int $bookId, LibroRepository $libroRepository): Response{
        $book = $libroRepository->find($bookId);
        $status = $book->getLectura();

        # Save book image
        $imageFile = $this->saveImageTemporalFile($book->getPortada());

        return $this->render('book/book.html.twig', [
            'controller_name' => 'BookController',
            'book' => $book,
            'status' => $status,
            'image' => $imageFile
        ]);
    }

    # Create book form
    #[Route('/createBook', methods: ['GET'],  name: 'app_createBook')]
    public function createBook(LanguageRepository $languageRepository): Response{

        $languages = $languageRepository->findAll();

        return $this->render('book/createBook.html.twig', [
            'controller_name' => 'BookController',
            'languages' => $languages
        ]);
    }

    # Create book
    #[Route('/createBook', methods: ['POST'],  name: 'post_createBook')]
    public function post_createBook(Request $request, LanguageRepository $languageRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response{
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $editorial = $request->request->get('editorial');
        
        $releaseDate = $request->request->get('releaseDate');
        $dateFormat = 'Y-m-d';
        $releaseDate = DateTime::createFromFormat($dateFormat, $releaseDate);

        # Language
        $languageName = $request->request->get('language');
        $language = $languageRepository->findOneBy(['acronym' => $languageName]);
        
        # Front page
        $frontPage = $request->files->get('frontPage')->getContent(); # tmp_name = Path where its stored the image

        $book = new Libro();
        $book->setTitulo($title);
        $book->setAutor($author);
        $book->setEditorial($editorial);
        $book->setFechaPublicacion($releaseDate);

        $book->setLengua($language);

        $book->setPortada($frontPage);

        $lectura = new Lectura();
        $lectura->setStatus("Deseado");
        $book->setLectura($lectura);

        if ( count($bookErrors = $validator->validate($book)) > 0){
            return new Response( (string) $bookErrors );
        }

        $entityManager->persist($book);
        $entityManager->flush();
        
        
        $this->addFlash(
            'success',
            'Book created succesfully'
        );

        return $this->redirectToRoute('app_index');
    }

    #[Route('/updateBook/{bookId}', methods: ['GET'],  name: 'app_updateBook')]
    public function updateBook(int $bookId, LibroRepository $libroRepository, LanguageRepository $languageRepository): Response{
        $book = $libroRepository->find($bookId);

        $imageFile = $this->saveImageTemporalFile($book->getPortada());

        $languages = $languageRepository->findAll();

        return $this->render('book/updateBook.html.twig', [
            'controller_name' => 'BookController',
            'book' => $book,
            'image' => $imageFile,
            'languages' => $languages
        ]);
    }
    
    #[Route('/updateBook/{bookId}', methods: ['POST'],  name: 'post_updateBook')]
    public function post_UpdateBook(int $bookId, Request $request, LibroRepository $libroRepository, LanguageRepository $languageRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response{
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $editorial = $request->request->get('editorial');
        
        $releaseDate = $request->request->get('releaseDate');
        $dateFormat = 'Y-m-d';
        $releaseDate = DateTime::createFromFormat($dateFormat, $releaseDate);

        # Language
        $languageAcronym = $request->request->get('language');
        $language = $languageRepository->findOneBy(['acronym' => $languageAcronym]);
        
        # Front page
        $frontPage = $request->files->get('frontPage'); # tmp_name = Path where its stored the image

        $book = $libroRepository->find($bookId);
        $book->setTitulo($title);
        $book->setAutor($author);
        $book->setEditorial($editorial);
        $book->setFechaPublicacion($releaseDate);

        $book->setLengua($language);

        if ($frontPage){
            $book->setPortada($frontPage->getContent());
        }

        if ( count($bookErrors = $validator->validate($book)) > 0){
            return new Response( (string) $bookErrors );
        }

        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Book updated succesfully'
        );

        return $this->redirectToRoute('app_book', ["bookId" => $bookId ]);
    }
}
