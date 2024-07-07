<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Repository\LanguageRepository;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Language;

class LanguageController extends AbstractController
{
    # Create language form
    #[Route('/createLanguage', methods: ['GET'],  name: 'app_createLanguage')]
    public function createLanguage(): Response{

        return $this->render('book/createLanguage.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    # Create language
    #[Route('/createLanguage', methods: ['POST'],  name: 'post_createLanguage')]
    public function post_createLanguage(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response{
        # Language
        $languageName = $request->request->get('language');
        
        $languageAcronym = $request->request->get('acronym');

        $language = new Language();
        $language->setName($languageName);
        $language->setAcronym($languageAcronym);

        if ( count($languageErrors = $validator->validate($language)) > 0){
            return new Response( (string) $languageErrors );
        }
        

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($language);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        
        return new Response('Saved new language with name '.$language->getName() . " and id " . $language->getId());
    }

    #[Route('/getLanguages', name: 'getLanguages')]
    public function getLanguages(LanguageRepository $languageRepository): Response{
        # Language
        $result = "";

        foreach ($languageRepository->findAll() as $language){
            $languageInfo = "1 " . $language->getName() . "_" . $language->getAcronym() . " (" . $language->getId() . ")" ;
            $result .= "\n" . $languageInfo;
        } 
        
        return new Response($result);
    }
}
