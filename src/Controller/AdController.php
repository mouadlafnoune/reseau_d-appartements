<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * @Route("/ads/new", name="ads_create")
     * @return Response
     */
    public function create(Request $request){
        $ad = new Ad();
       // $image = new Image();
       // $image->setUrl('http://placehold.it/400x200')
        //      ->setCaption('Titre 1');
        //$ad->addImage($image);  
        $form = $this->createForm(AdType::class, $ad);
                   
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          foreach($ad->getImages() as $image) {
              $image->setAd($ad);
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($image);
          }
  

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ad);
            $entityManager->flush();

            $this->addFlash('success',
            "Les modification de L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrées");

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }
        return $this->render('ad/new.html.twig',[
            'form' => $form->createView()
        ]);
   }
   
    /**
     * @Route("/ads/edit/{slug}", name="ads_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ad $ad): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($image);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée");

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     */
    public function show(Ad $ad){
         //$ad = $repo->findOneBySlug($slug);
         return $this->render('ad/show.html.twig', [
             'ad' => $ad
         ]);
    }

    
}