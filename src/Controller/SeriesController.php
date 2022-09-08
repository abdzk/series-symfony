<?php

namespace App\Controller;

use App\Entity\Serie;


use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SeriesController extends AbstractController
{
    #[Route('/series', name: 'serie_list')]
    public function list(SerieRepository $serieRepository): Response
    {
        $serie= $serieRepository->findBestSeries();

        return $this->render('series/list.html.twig', [
            "series"=>$serie //afficher le tableau dans le twig
        ]);
    }

    #[Route('/series/details/{id}', name: 'serie_details')]
    public function details(int $id, SerieRepository $serieRepository): Response
    {
            $serie = $serieRepository->find($id);


        return $this->render('series/details.html.twig', [

            "serie"=>$serie
        ]);
    }

    #[Route('/series/create', name: 'serie_create')]
    public function create(Request $request,EntityManagerInterface $entityManager): Response
    {

        $serie = new Serie();
        $serie->setDateCreated(new \DateTime());
        $serieForm = $this->createForm(SerieType::class,$serie);


        $serieForm->handleRequest($request);


        if($serieForm->isSubmitted() && $serieForm->isValid()){


            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('succes','Serie added,good job!!');
            return $this->redirectToRoute('serie_details',['id'=> $serie->getId()]);


        }

        return $this->render('series/create.html.twig', [
            'serieForm'=>$serieForm->createView()
        ]);
    }



    #[Route('/series/delete/{id}', name: 'serie_delete')]
    public function delete(Serie $serie, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home', []);
    }

    #[Route('/series/demo', name: 'em-demo')]
    public function demo(EntityManagerInterface $entityManager): Response
    {
                //création d'une instance de mon entité
                $serie = new Serie();

                //hydrate toutes les propriétes
                $serie->setName('hyt');
                $serie->setBackdrop('fegg');
                $serie->setPoster('jkjkj');
                $serie->setDateCreated(new \DateTime());
                $serie->setFirstAirDate(new \DateTime("- 1 year"));
                $serie->setLastAireDate(new \DateTime("- 6 month"));
                $serie->setGenres("drama");
                $serie->setOverview('ffhfh');
                $serie->setVote(8.2);
                $serie->setStatus('canceled');
                $serie->setTmdbId(445465456);
                $serie->setPopularity('yuyiiiuy');




                $entityManager->remove($serie);
                $entityManager->flush();

        return $this->render('series/create.html.twig', [

        ]);
    }
}
