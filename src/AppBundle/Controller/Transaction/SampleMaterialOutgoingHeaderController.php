<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SampleMaterialOutgoingHeader;
use AppBundle\Form\Transaction\SampleMaterialOutgoingHeaderType;
use AppBundle\Grid\Transaction\SampleMaterialOutgoingHeaderGridType;

/**
 * @Route("/transaction/sample_material_outgoing_header")
 */
class SampleMaterialOutgoingHeaderController extends Controller
{
    /**
     * @Route("/grid", name="transaction_sample_material_outgoing_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SampleMaterialOutgoingHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SampleMaterialOutgoingHeaderGridType::class, $repository, $request);

        return $this->render('transaction/sample_material_outgoing_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_sample_material_outgoing_header_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/sample_material_outgoing_header/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_sample_material_outgoing_header_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $sampleMaterialOutgoingHeader = new SampleMaterialOutgoingHeader();
        
        $sampleMaterialOutgoingHeaderService = $this->get('app.transaction.sample_material_outgoing_header_form');
        $form = $this->createForm(SampleMaterialOutgoingHeaderType::class, $sampleMaterialOutgoingHeader, array(
            'service' => $sampleMaterialOutgoingHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $sampleMaterialOutgoingHeaderService->save($sampleMaterialOutgoingHeader);

            return $this->redirectToRoute('transaction_sample_material_outgoing_header_show', array('id' => $sampleMaterialOutgoingHeader->getId()));
        }

        return $this->render('transaction/sample_material_outgoing_header/new.'.$_format.'.twig', array(
            'sampleMaterialOutgoingHeader' => $sampleMaterialOutgoingHeader,
            'form' => $form->createView(),
            'sampleMaterialOutgoingDetailsCount' => 0,
        ));
    }

    /**
     * @Route("/{id}", name="transaction_sample_material_outgoing_header_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader)
    {
        return $this->render('transaction/sample_material_outgoing_header/show.html.twig', array(
            'sampleMaterialOutgoingHeader' => $sampleMaterialOutgoingHeader,
        ));
    }

    /**
     * @Route("/{id}/edit.{_format}", name="transaction_sample_material_outgoing_header_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader, $_format = 'html')
    {
        $sampleMaterialOutgoingDetailsCount = $sampleMaterialOutgoingHeader->getSampleMaterialOutgoingDetails()->count();
        
        $sampleMaterialOutgoingHeaderService = $this->get('app.transaction.sample_material_outgoing_header_form');
        $form = $this->createForm(SampleMaterialOutgoingHeaderType::class, $sampleMaterialOutgoingHeader, array(
            'service' => $sampleMaterialOutgoingHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $sampleMaterialOutgoingHeaderService->save($sampleMaterialOutgoingHeader);

            return $this->redirectToRoute('transaction_sample_material_outgoing_header_show', array('id' => $sampleMaterialOutgoingHeader->getId()));
        }

        return $this->render('transaction/sample_material_outgoing_header/edit.'.$_format.'.twig', array(
            'sampleMaterialOutgoingHeader' => $sampleMaterialOutgoingHeader,
            'form' => $form->createView(),
            'sampleMaterialOutgoingDetailsCount' => $sampleMaterialOutgoingDetailsCount,
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_sample_material_outgoing_header_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader)
    {
        $sampleMaterialOutgoingHeaderService = $this->get('app.transaction.sample_material_outgoing_header_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $sampleMaterialOutgoingHeaderService->delete($sampleMaterialOutgoingHeaderService);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_sample_material_outgoing_header_index');
        }

        return $this->render('transaction/sample_material_outgoing_header/delete.html.twig', array(
            'sampleMaterialOutgoingHeader' => $sampleMaterialOutgoingHeader,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/{id}/memo", name="transaction_sample_material_outgoing_header_memo", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function memoAction(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader)
    {
        return $this->render('transaction/sample_material_outgoing_header/memo.html.twig', array(
            'sampleMaterialOutgoingHeader' => $sampleMaterialOutgoingHeader,
        ));
    }
}
