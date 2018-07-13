<?php

namespace AppBundle\Controller\Master;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Master\MaterialCategory;
use AppBundle\Form\Master\MaterialCategoryType;
use AppBundle\Grid\Master\MaterialCategoryGridType;

/**
 * @Route("/master/material_category")
 */
class MaterialCategoryController extends Controller
{
    /**
     * @Route("/grid", name="master_material_category_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(MaterialCategory::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(MaterialCategoryGridType::class, $repository, $request);

        return $this->render('master/material_category/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="master_material_category_index")
     * @Method("GET")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function indexAction()
    {
        return $this->render('master/material_category/index.html.twig');
    }

    /**
     * @Route("/new", name="master_material_category_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function newAction(Request $request)
    {
        $materialCategory = new MaterialCategory();
        $form = $this->createForm(MaterialCategoryType::class, $materialCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(MaterialCategory::class);
            $repository->add($materialCategory);

            return $this->redirectToRoute('master_material_category_show', array('id' => $materialCategory->getId()));
        }

        return $this->render('master/material_category/new.html.twig', array(
            'materialCategory' => $materialCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="master_material_category_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_MASTER')")
     */
    public function showAction(MaterialCategory $materialCategory)
    {
        return $this->render('master/material_category/show.html.twig', array(
            'materialCategory' => $materialCategory,
        ));
    }

    /**
     * @Route("/{id}/edit", name="master_material_category_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function editAction(Request $request, MaterialCategory $materialCategory)
    {
        $form = $this->createForm(MaterialCategoryType::class, $materialCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(MaterialCategory::class);
            $repository->update($materialCategory);

            return $this->redirectToRoute('master_material_category_show', array('id' => $materialCategory->getId()));
        }

        return $this->render('master/material_category/edit.html.twig', array(
            'materialCategory' => $materialCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="master_material_category_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_MASTER')")
     */
    public function deleteAction(Request $request, MaterialCategory $materialCategory)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(MaterialCategory::class);
                $repository->remove($materialCategory);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('master_material_category_index');
        }

        return $this->render('master/material_category/delete.html.twig', array(
            'materialCategory' => $materialCategory,
            'form' => $form->createView(),
        ));
    }
}
