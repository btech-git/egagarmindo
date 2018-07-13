<?php

namespace AppBundle\Controller\Master;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Master\Material;
use AppBundle\Form\Master\MaterialType;
use AppBundle\Grid\Master\MaterialGridType;

/**
 * @Route("/master/material")
 */
class MaterialController extends Controller
{
    /**
     * @Route("/grid", name="master_material_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Material::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(MaterialGridType::class, $repository, $request);

        return $this->render('master/material/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="master_material_index")
     * @Method("GET")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function indexAction()
    {
        return $this->render('master/material/index.html.twig');
    }

    /**
     * @Route("/new", name="master_material_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function newAction(Request $request)
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(Material::class);
            $repository->add($material);

            return $this->redirectToRoute('master_material_show', array('id' => $material->getId()));
        }

        return $this->render('master/material/new.html.twig', array(
            'material' => $material,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="master_material_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function showAction(Material $material)
    {
        return $this->render('master/material/show.html.twig', array(
            'material' => $material,
        ));
    }

    /**
     * @Route("/{id}/edit", name="master_material_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function editAction(Request $request, Material $material)
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(Material::class);
            $repository->update($material);

            return $this->redirectToRoute('master_material_show', array('id' => $material->getId()));
        }

        return $this->render('master/material/edit.html.twig', array(
            'material' => $material,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="master_material_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function deleteAction(Request $request, Material $material)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(Material::class);
                $repository->remove($material);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('master_material_index');
        }

        return $this->render('master/material/delete.html.twig', array(
            'material' => $material,
            'form' => $form->createView(),
        ));
    }
}
