<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        // Genera un nombre único para la imagen
        
        // Generate new unique name
        // $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();

        $fileName = $fileName . '.' . $image->guessExtension();

        // Define el directorio de destino
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/';

        // Mueve la imagen al directorio de destino
        $image->move(
            $destination,
            $fileName
        );

        // Aquí puedes guardar la información de la imagen en la base de datos si es necesario
        //Para obtener los datos en formato blob hay que hacer file_get_contents($image->getPathname());

        return $image;
    }
    

    
    # Falta lógica 1
    #[Route('/', name: 'app_index')]
    public function index(LibroRepository $libroRepository): Response{
        # Get all books
        $books = $libroRepository->findAll();

        foreach ( $books as  $book){
            echo "1";
        }
        # Save all books images

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books
        ]);
    }

    # Falta lógica 1
    #[Route('/book/{bookId}', name: 'app_book')]
    public function book(int $bookId): Response{
        $book = Libro.getById($bookId);
        $status = $book.getLectura()[0]; #We only care about the first result

        # Save book image

        return $this->render('book/book.html.twig', [
            'controller_name' => 'BookController',
            'book' => $book,
            'status' => $status
        ]);
    }

    # Create book form
    #[Route('/createBook', methods: ['GET'],  name: 'app_createBook')]
    public function createBook(): Response{

        return $this->render('book/createBook.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    # Create book
    #[Route('/createBook', methods: ['POST'],  name: 'post_createBook')]
    public function post_createBook(Request $request, LanguageRepository $languageRepository): Response{
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $editorial = $request->request->get('editorial');
        
        $releaseDate = $request->request->get('releaseDate');
        $dateFormat = 'Y-m-d';
        $releaseDate = DateTime::createFromFormat($dateFormat, $releaseDate);

        # Language
        $languageName = $request->request->get('language');
        $language = $languageRepository->findOneBy(['name' => $languageName]);
        
        # Front page
        $frontPage = $request->files->get('frontPage')->getContent(); # tmp_name = Path where its stored the image
        
        # dd($language);

        $book = new Libro();
        $book->setTitulo($title);
        $book->setAutor($author);
        $book->setEditorial($editorial);
        $book->setFechaPublicacion($releaseDate);

        $book->setLengua($language);

        $book->setPortada($frontPage);


        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        # $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        # $entityManager->flush();
        
        return new Response('Saved new product with id '.$book->getFechaPublicacion()->format('Y-m-d'));
    }

    #[Route('/updateBook/{bookId}', methods: ['GET'],  name: 'app_updateBook')]
    public function updateBook(int $bookId, LibroRepository $libroRepository): Response{
        $book = $libroRepository->find($bookId);

        return $this->render('book/updateBook.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    
    #[Route('/updateBook/{bookId}', methods: ['POST'],  name: 'post_updateBook')]
    public function post_UpdateBook(int $bookId, LibroRepository $libroRepository): Response{
        # Get book + update it
        $book = $libroRepository.find($bookId);

        # Update it (Think what parameters should change)

        return $this->render('book/updateBook.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }


    #[Route('/updateReadingStatus/{bookId}', methods: ['GET'], name: 'app_updateReadingStatus')]
    public function updateReadingStatus(int $bookId, LibroRepository $libroRepository): Response{
        # Get book
        $book = $libroRepository->find($bookId);

        return $this->render('book/updateReadingStatus.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/updateReadingStatus/{bookId}', methods: ['POST'], name: 'post_updateReadingStatus')]
    public function post_UpdateReadingStatus(int $bookId, LibroRepository $libroRepository): Response{
        # Get book + update it
        $book = $libroRepository->find($bookId);

        return $this->render('book/updateReadingStatus.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    # Create book form
    #[Route('/createLanguage', methods: ['GET'],  name: 'app_createLanguage')]
    public function createLanguage(): Response{

        return $this->render('book/createLanguage.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    # Create book
    #[Route('/createLanguage', methods: ['POST'],  name: 'post_createLanguage')]
    public function post_createLanguage(Request $request, EntityManagerInterface $entityManager): Response{
        # Language
        $languageName = $request->request->get('language');
        
        $languageAcronym = $request->request->get('acronym');

        $language = new Language();
        $language->setName($languageName);
        $language->setAcronym($languageAcronym);
        

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($language);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        
        return new Response('Saved new language with name '.$language->getName() . " and id " . $language->getId());
    }
}
