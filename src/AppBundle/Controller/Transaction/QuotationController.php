<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\Quotation;
use AppBundle\Form\Transaction\QuotationType;
use AppBundle\Grid\Transaction\QuotationGridType;

/**
 * @Route("/transaction/quotation")
 */
class QuotationController extends Controller
{
    /**
     * @Route("/grid", name="transaction_quotation_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Quotation::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(QuotationGridType::class, $repository, $request);

        return $this->render('transaction/quotation/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_quotation_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/quotation/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_quotation_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $quotation = new Quotation();
        
        $quotationService = $this->get('app.transaction.quotation_form');
        $form = $this->createForm(QuotationType::class, $quotation, array(
            'service' => $quotationService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $quotationService->save($quotation);

            return $this->redirectToRoute('transaction_quotation_show', array('id' => $quotation->getId()));
        }

        return $this->render('transaction/quotation/new.'.$_format.'.twig', array(
            'quotation' => $quotation,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}", name="transaction_quotation_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(Quotation $quotation)
    {
        return $this->render('transaction/quotation/show.html.twig', array(
            'quotation' => $quotation,
        ));
    }

    /**
     * @Route("/{id}/edit.{_format}", name="transaction_quotation_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, Quotation $quotation, $_format = 'html')
    {
        $quotationService = $this->get('app.transaction.quotation_form');
        $form = $this->createForm(QuotationType::class, $quotation, array(
            'service' => $quotationService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $quotationService->save($quotation);

            return $this->redirectToRoute('transaction_quotation_show', array('id' => $quotation->getId()));
        }

        return $this->render('transaction/quotation/edit.'.$_format.'.twig', array(
            'quotation' => $quotation,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_quotation_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, Quotation $quotation)
    {
        $quotationService = $this->get('app.transaction.quotation_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $quotationService->delete($quotation);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_quotation_index');
        }

        return $this->render('transaction/quotation/delete.html.twig', array(
            'quotation' => $quotation,
            'form' => $form->createView(),
        ));
    }
}
