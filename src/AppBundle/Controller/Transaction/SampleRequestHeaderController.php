<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SampleRequestHeader;
use AppBundle\Form\Transaction\SampleRequestHeaderType;
use AppBundle\Grid\Transaction\SampleRequestHeaderGridType;

/**
 * @Route("/transaction/sample_request_header")
 */
class SampleRequestHeaderController extends Controller
{
    /**
     * @Route("/grid", name="transaction_sample_request_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SampleRequestHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SampleRequestHeaderGridType::class, $repository, $request);

        return $this->render('transaction/sample_request_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_sample_request_header_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/sample_request_header/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_sample_request_header_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $sampleRequestHeader = new SampleRequestHeader();
        
        $sampleRequestHeaderService = $this->get('app.transaction.sample_request_header_form');
        $form = $this->createForm(SampleRequestHeaderType::class, $sampleRequestHeader, array(
            'service' => $sampleRequestHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $sampleRequestHeaderService->save($sampleRequestHeader);

            return $this->redirectToRoute('transaction_sample_request_header_show', array('id' => $sampleRequestHeader->getId()));
        }

        return $this->render('transaction/sample_request_header/new.'.$_format.'.twig', array(
            'sampleRequestHeader' => $sampleRequestHeader,
            'form' => $form->createView(),
            'sampleRequestDetailsCount' => 0,
        ));
    }

    /**
     * @Route("/{id}", name="transaction_sample_request_header_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(SampleRequestHeader $sampleRequestHeader)
    {
        return $this->render('transaction/sample_request_header/show.html.twig', array(
            'sampleRequestHeader' => $sampleRequestHeader,
        ));
    }

    /**
     * @Route("/{id}/edit.{_format}", name="transaction_sample_request_header_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, SampleRequestHeader $sampleRequestHeader, $_format = 'html')
    {
        $sampleRequestDetailsCount = $sampleRequestHeader->getSampleRequestDetails()->count();
        
        $sampleRequestHeaderService = $this->get('app.transaction.sample_request_header_form');
        $form = $this->createForm(SampleRequestHeaderType::class, $sampleRequestHeader, array(
            'service' => $sampleRequestHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $sampleRequestHeaderService->save($sampleRequestHeader);

            return $this->redirectToRoute('transaction_sample_request_header_show', array('id' => $sampleRequestHeader->getId()));
        }

        return $this->render('transaction/sample_request_header/edit.'.$_format.'.twig', array(
            'sampleRequestHeader' => $sampleRequestHeader,
            'form' => $form->createView(),
            'sampleRequestDetailsCount' => $sampleRequestDetailsCount,
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_sample_request_header_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, SampleRequestHeader $sampleRequestHeader)
    {
        $sampleRequestHeaderService = $this->get('app.transaction.sample_request_header_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $sampleRequestHeaderService->delete($sampleRequestHeader);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_sample_request_header_index');
        }

        return $this->render('transaction/sample_request_header/delete.html.twig', array(
            'sampleRequestHeader' => $sampleRequestHeader,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/{id}/memo", name="transaction_sample_request_header_memo", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function memoAction(SampleRequestHeader $sampleRequestHeader)
    {
        return $this->render('transaction/sample_request_header/memo.html.twig', array(
            'sampleRequestHeader' => $sampleRequestHeader,
        ));
    }
}
