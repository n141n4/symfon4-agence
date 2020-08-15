<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repository)
    {

        $this->repository = $repository;
    }
    /**
     * @Route("/biens", name="property.index")
     */
    public function index(): Response
    {
        /*
         * Enregistrement des nouveaux biens
        $property = new Property();
        $property->setTitle('Mon premier bien')
            ->setPrice(200000)
            ->setRooms(4)
            ->setBedRooms(3)
            ->setDescription('Une petite description')
            ->setSurface(60)
            ->setFloor(4)
            ->setHeat(1)
            ->setCity('Itaosy')
            ->setAddress('IPA 67 Anosimasina')
            ->setPostalCode('Antananarivo 102');
        $em = $this->getDoctrine()->getManager();
        $em->persist($property);
        $em->flush(); // save all modifications into the database
         * */

        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
        ]);
    }

    /**
     * @Route("/biens/{id}", name="property.show")
     * @return Response
     */
    public  function show($id): Response
    {
        $property = $this->repository->find($id); // récupérer un bien
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
        ]);
    }
}
