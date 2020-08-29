<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        /**
         * RECHERCHE DES BIENS
         */

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

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

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 1
        );

        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
            'properties' => $properties,
            'form' =>   $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{id}", name="property.show")
     * @param $id
     * @param Property $property
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public  function show($id, Property $property, Request $request, ContactNotification $notification): Response
    {

        $property = $this->repository->find($id); // récupérer un bien

        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a été bien envoyé');
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId()
            ]);
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
            'form' =>  $form->createView()
        ]);
    }
}
