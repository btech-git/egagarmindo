<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SampleRequestDetail;
use AppBundle\Form\Transaction\SampleRequestDetailType;
use AppBundle\Grid\Transaction\SampleRequestDetailGridType;

/**
 * @Route("/transaction/sample_request_detail")
 */
class SampleRequestDetailController extends Controller
{
    /**
     * @Route("/grid", name="transaction_sample_request_detail_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SampleRequestDetail::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SampleRequestDetailGridType::class, $repository, $request);

        return $this->render('transaction/sample_request_detail/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_sample_request_detail_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/sample_request_detail/index.html.twig');
    }

    /**
     * @Route("/new", name="transaction_sample_request_detail_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request)
    {
        $sampleRequestDetail = new SampleRequestDetail();
        $form = $this->createForm(SampleRequestDetailType::class, $sampleRequestDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(SampleRequestDetail::class);
            $repository->add($sampleRequestDetail);

            return $this->redirectToRoute('transaction_sample_request_detail_show', array('id' => $sampleRequestDetail->getId()));
        }

        return $this->render('transaction/sample_request_detail/new.html.twig', array(
            'sampleRequestDetail' => $sampleRequestDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_sample_request_detail_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(SampleRequestDetail $sampleRequestDetail)
    {
        return $this->render('transaction/sample_request_detail/show.html.twig', array(
            'sampleRequestDetail' => $sampleRequestDetail,
        ));
    }

    /**
     * @Route("/{id}/edit", name="transaction_sample_request_detail_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, SampleRequestDetail $sampleRequestDetail)
    {
        $form = $this->createForm(SampleRequestDetailType::class, $sampleRequestDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(SampleRequestDetail::class);
            $repository->update($sampleRequestDetail);

            return $this->redirectToRoute('transaction_sample_request_detail_show', array('id' => $sampleRequestDetail->getId()));
        }

        return $this->render('transaction/sample_request_detail/edit.html.twig', array(
            'sampleRequestDetail' => $sampleRequestDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_sample_request_detail_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, SampleRequestDetail $sampleRequestDetail)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(SampleRequestDetail::class);
                $repository->remove($sampleRequestDetail);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_sample_request_detail_index');
        }

        return $this->render('transaction/sample_request_detail/delete.html.twig', array(
            'sampleRequestDetail' => $sampleRequestDetail,
            'form' => $form->createView(),
        ));
    }
}
