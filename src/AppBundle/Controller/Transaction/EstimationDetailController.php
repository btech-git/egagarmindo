<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\EstimationDetail;
use AppBundle\Form\Transaction\EstimationDetailType;
use AppBundle\Grid\Transaction\EstimationDetailGridType;

/**
 * @Route("/transaction/estimation_detail")
 */
class EstimationDetailController extends Controller
{
    /**
     * @Route("/grid", name="transaction_estimation_detail_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(EstimationDetail::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(EstimationDetailGridType::class, $repository, $request);

        return $this->render('transaction/estimation_detail/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_estimation_detail_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/estimation_detail/index.html.twig');
    }

    /**
     * @Route("/new", name="transaction_estimation_detail_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request)
    {
        $estimationDetail = new EstimationDetail();
        $form = $this->createForm(EstimationDetailType::class, $estimationDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(EstimationDetail::class);
            $repository->add($estimationDetail);

            return $this->redirectToRoute('transaction_estimation_detail_show', array('id' => $estimationDetail->getId()));
        }

        return $this->render('transaction/estimation_detail/new.html.twig', array(
            'estimationDetail' => $estimationDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_estimation_detail_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(EstimationDetail $estimationDetail)
    {
        return $this->render('transaction/estimation_detail/show.html.twig', array(
            'estimationDetail' => $estimationDetail,
        ));
    }

    /**
     * @Route("/{id}/edit", name="transaction_estimation_detail_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, EstimationDetail $estimationDetail)
    {
        $form = $this->createForm(EstimationDetailType::class, $estimationDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(EstimationDetail::class);
            $repository->update($estimationDetail);

            return $this->redirectToRoute('transaction_estimation_detail_show', array('id' => $estimationDetail->getId()));
        }

        return $this->render('transaction/estimation_detail/edit.html.twig', array(
            'estimationDetail' => $estimationDetail,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_estimation_detail_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, EstimationDetail $estimationDetail)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository(EstimationDetail::class);
                $repository->remove($estimationDetail);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_estimation_detail_index');
        }

        return $this->render('transaction/estimation_detail/delete.html.twig', array(
            'estimationDetail' => $estimationDetail,
            'form' => $form->createView(),
        ));
    }
}
