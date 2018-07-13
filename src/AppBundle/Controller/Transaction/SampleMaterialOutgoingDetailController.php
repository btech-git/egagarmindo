<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SampleMaterialOutgoingDetail;
use AppBundle\Form\Transaction\SampleMaterialOutgoingDetailType;
use AppBundle\Grid\Transaction\SampleMaterialOutgoingDetailGridType;

/**
 * @Route("/transaction/sample_material_outgoing_detail")
 */
class SampleMaterialOutgoingDetailController extends Controller
{
    /**
     * @Route("/grid", name="transaction_sample_material_outgoing_detail_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SampleMaterialOutgoingDetail::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SampleMaterialOutgoingDetailGridType::class, $repository, $request);

        return $this->render('transaction/sample_material_outgoing_detail/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_sample_material_outgoing_detail_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/sample_material_outgoing_detail/index.html.twig');
    }

    /**
     * @Route("/new", name="transaction_sample_material_outgoing_detail_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request)
    {
        $sampleMaterialOutgoingDetail = new SampleMaterialOutgoingDetail();
        $form = $this->createForm(SampleMaterialOutgoingDetailType::class, $sampleMaterialOutgoingDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(SampleMaterialOutgoingDetail::class);
            $repository->add($sampleMaterialOutgoingDetail);

            return $this->redirectToRoute('transaction_sample_material_outgoing_detail_show', array('id' => $sampleMaterialOutgoingDetail->getId()));
        }

        return $this->render('transaction/sample_material_outgoing_detail/new.html.twig', array(
            'sampleMaterialOutgoingDetail' => $sampleMaterialOutgoingDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_sample_material_outgoing_detail_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(SampleMaterialOutgoingDetail $sampleMaterialOutgoingDetail)
    {
        return $this->render('transaction/sample_material_outgoing_detail/show.html.twig', array(
            'sampleMaterialOutgoingDetail' => $sampleMaterialOutgoingDetail,
        ));
    }

    /**
     * @Route("/{id}/edit", name="transaction_sample_material_outgoing_detail_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, SampleMaterialOutgoingDetail $sampleMaterialOutgoingDetail)
    {
        $form = $this->createForm(SampleMaterialOutgoingDetailType::class, $sampleMaterialOutgoingDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(SampleMaterialOutgoingDetail::class);
            $repository->update($sampleMaterialOutgoingDetail);

            return $this->redirectToRoute('transaction_sample_material_outgoing_detail_show', array('id' => $sampleMaterialOutgoingDetail->getId()));
        }

        return $this->render('transaction/sample_material_outgoing_detail/edit.html.twig', array(
            'sampleMaterialOutgoingDetail' => $sampleMaterialOutgoingDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_sample_material_outgoing_detail_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, SampleMaterialOutgoingDetail $sampleMaterialOutgoingDetail)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(SampleMaterialOutgoingDetail::class);
                $repository->remove($sampleMaterialOutgoingDetail);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_sample_material_outgoing_detail_index');
        }

        return $this->render('transaction/sample_material_outgoing_detail/delete.html.twig', array(
            'sampleMaterialOutgoingDetail' => $sampleMaterialOutgoingDetail,
            'form' => $form->createView(),
        ));
    }
}
