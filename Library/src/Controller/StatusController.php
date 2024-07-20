<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Repository\LibroRepository;

use App\Entity\Libro;
use App\Entity\Lectura;

use DateTime;


class StatusController extends AbstractController
{
    private function saveImageTemporalFile($image, $fileName = "temporal"){
        $imagesFolder = $this->getParameter('kernel.project_dir').'/public/image/';

        $fileName = $fileName . ".jpg";

        file_put_contents($imagesFolder . $fileName, $image);

        return $fileName;
    }
    

    #[Route('/updateReadingStatus/{bookId}', methods: ['GET'], name: 'app_updateReadingStatus')]
    public function updateReadingStatus(int $bookId, LibroRepository $libroRepository): Response{
        # Get book
        $book = $libroRepository->find($bookId);
        $status = $book->getLectura();

        
        $imageFile = $this->saveImageTemporalFile($book->getPortada());

        return $this->render('book/updateReadingStatus.html.twig', [
            'controller_name' => 'BookController',
            'book' => $book,
            'status' => $status,
            'allStatus' => Lectura::$allStatus,
            "image" => $imageFile
        ]);
    }
    

    #[Route('/updateReadingStatus/{bookId}', methods: ['POST'], name: 'post_updateReadingStatus')]
    public function post_UpdateReadingStatus(int $bookId, LibroRepository $libroRepository, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response{
        # Get book + update it
        $book = $libroRepository->find($bookId);
        $status = $book->getLectura();

        $statusType = $request->request->get("status");
        $startingDate = $request->request->get("startDate");
        $finishDate = $request->request->get("finishDate");

        $dateFormat = 'Y-m-d';

        $startingDate = ($startingDate != "") ? DateTime::createFromFormat($dateFormat, $startingDate) : null;
        $finishDate = ($finishDate != "") ? DateTime::createFromFormat($dateFormat, $finishDate) : null;


        $status->setStatus($statusType);
        $status->setFechaComienzo($startingDate);
        $status->setFechaFinal($finishDate);

        if ( count($statusErrors = $validator->validate($status)) > 0){
            return new Response( (string) $statusErrors );
        }

        $entityManager->persist($status);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Status updated succesfully'
        );

        return $this->redirectToRoute('app_book', ["bookId" => $bookId ]);
    }
}
