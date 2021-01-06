<?php

namespace App\Controller;

use App\Entity\Argonaute;
use App\Form\ArgonauteType;
use App\Repository\ArgonauteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $em;
    private ArgonauteRepository $argonauteRepository;

    public function __construct(
        EntityManagerInterface $em,
    ArgonauteRepository $argonauteRepository)
    {
        $this->em = $em;
        $this->argonauteRepository = $argonauteRepository;
    }


    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $argonaute = new Argonaute();

        $argonauteForm = $this->createForm(ArgonauteType::class, $argonaute, [
            'label' => false
        ]);

        $argonauteForm->handleRequest($request);

        if ($argonauteForm->isSubmitted() && $argonauteForm->isValid()) {
            $this->em->persist($argonaute);
            $this->em->flush();
        }

        $argonautes = $this->argonauteRepository->findAll();


        return $this->render('home/index.html.twig', [
            'argonauteForm' => $argonauteForm->createView(),
            'argonautes' => $argonautes
        ]);
    }
}
