<?php

namespace App\Controller\Admin;

use App\Entity\PropertyOption;
use App\Form\PropertyOptionType;
use App\Repository\PropertyOptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/option")
 */
class AdminPropertyOptionController extends AbstractController
{
    /**
     * @Route("/", name="admin.option.index", methods={"GET"})
     * @param PropertyOptionRepository $propertyOptionRepository
     * @return Response
     */
    public function index(PropertyOptionRepository $propertyOptionRepository): Response
    {
        return $this->render('admin/option/index.html.twig', [
            'options' => $propertyOptionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.option.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $propertyOption = new PropertyOption();
        $form = $this->createForm(PropertyOptionType::class, $propertyOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($propertyOption);
            $entityManager->flush();

            return $this->redirectToRoute('property_option_index');
        }

        return $this->render('admin/option/create.html.twig', [
            'property_option' => $propertyOption,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.option.edit", methods={"GET","POST"})
     * @param Request $request
     * @param PropertyOption $propertyOption
     * @return Response
     */
    public function edit(Request $request, PropertyOption $propertyOption): Response
    {
        $form = $this->createForm(PropertyOptionType::class, $propertyOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin.option.index', ['id' => $propertyOption->getId()] );
        }

        return $this->render('admin/option/edit.html.twig', [
            'property_option' => $propertyOption,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.option.delete", methods={"DELETE"})
     */
    public function delete(Request $request, PropertyOption $propertyOption): Response
    {
        if ($this->isCsrfTokenValid('delete'.$propertyOption->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($propertyOption);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.option.index');
    }
}
