<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(WishRepository $repo): Response
    {
        $wishes = $repo->findBy(
            ['isPublished' => true],
            ['dateCreated' => 'ASC']
        );

        return $this->render('main/about.html.twig',[
            'wishes'=>$wishes]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(Wish $wish): Response
    {
        return $this->render('main/detail.html.twig', [
            'wish' => $wish,
        ]);
    }

    /**
     * @Route("/ajouter", name="ajouter")
     */
    public function ajouter(Request $request): Response
    {
        $wish = new Wish();
        $wish->setIsPublished(true);
        $formWish = $this->createForm(WishType::class, $wish);
        $formWish->handleRequest($request);
        if ($formWish->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($wish);
            $em->flush();
            return $this->redirectToRoute('detail', ['id' => $wish->getId()]);
        }
        return $this->render('main/ajouter.html.twig',
            ['formWish'=> $formWish->createView()]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
