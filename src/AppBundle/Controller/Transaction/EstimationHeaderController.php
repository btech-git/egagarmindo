<?php

namespace AppBundle\Controller\Transaction;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\EstimationHeader;
use AppBundle\Form\Transaction\EstimationHeaderType;
use AppBundle\Grid\Transaction\EstimationHeaderGridType;

/**
 * @Route("/transaction/estimation_header")
 */
class EstimationHeaderController extends Controller
{
    /**
     * @Route("/grid", name="transaction_estimation_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(EstimationHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(EstimationHeaderGridType::class, $repository, $request);

        return $this->render('transaction/estimation_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }

    /**
     * @Route("/", name="transaction_estimation_header_index")
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function indexAction()
    {
        return $this->render('transaction/estimation_header/index.html.twig');
    }

    /**
     * @Route("/new.{_format}", name="transaction_estimation_header_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function newAction(Request $request, $_format = 'html')
    {
        $estimationHeader = new EstimationHeader();
        
        $estimationHeaderService = $this->get('app.transaction.estimation_header_form');
        $form = $this->createForm(EstimationHeaderType::class, $estimationHeader, array(
            'service' => $estimationHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $estimationHeaderService->save($estimationHeader);

            return $this->redirectToRoute('transaction_estimation_header_show', array('id' => $estimationHeader->getId()));
        }

        return $this->render('transaction/estimation_header/new.'.$_format.'.twig', array(
            'estimationHeader' => $estimationHeader,
            'form' => $form->createView(),
            'estimationDetailsCount' => 0,
        ));
    }

    /**
     * @Route("/{id}", name="transaction_estimation_header_show", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function showAction(EstimationHeader $estimationHeader)
    {
        return $this->render('transaction/estimation_header/show.html.twig', array(
            'estimationHeader' => $estimationHeader,
        ));
    }

    /**
     * @Route("/{id}/edit.{_format}", name="transaction_estimation_header_edit", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function editAction(Request $request, EstimationHeader $estimationHeader, $_format = 'html')
    {
        $estimationDetailsCount = $estimationHeader->getEstimationDetails()->count();
        
        $estimationHeaderService = $this->get('app.transaction.estimation_header_form');
        $form = $this->createForm(EstimationHeaderType::class, $estimationHeader, array(
            'service' => $estimationHeaderService,
            'init' => array('year' => date('y'), 'month' => date('m'), 'staff' => $this->getUser()),
        ));
        $form->handleRequest($request);

        if ($_format === 'html' && $form->isSubmitted() && $form->isValid()) {
            $estimationHeaderService->save($estimationHeader);

            return $this->redirectToRoute('transaction_estimation_header_show', array('id' => $estimationHeader->getId()));
        }

        return $this->render('transaction/estimation_header/edit.'.$_format.'.twig', array(
            'estimationHeader' => $estimationHeader,
            'form' => $form->createView(),
            'estimationDetailsCount' => $estimationDetailsCount,
        ));
    }

    /**
     * @Route("/{id}/delete", name="transaction_estimation_header_delete", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function deleteAction(Request $request, EstimationHeader $estimationHeader)
    {
        $estimationHeaderService = $this->get('app.transaction.estimation_header_form');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $estimationHeaderService->delete($estimationHeader);

                $this->addFlash('success', array('title' => 'Success!', 'message' => 'The record was deleted successfully.'));
            } else {
                $this->addFlash('danger', array('title' => 'Error!', 'message' => 'Failed to delete the record.'));
            }

            return $this->redirectToRoute('transaction_estimation_header_index');
        }

        return $this->render('transaction/estimation_header/delete.html.twig', array(
            'estimationHeader' => $estimationHeader,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/{id}/memo", name="transaction_estimation_header_memo", requirements={"id": "\d+"})
     * @Method("GET")
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function memoAction(EstimationHeader $estimationHeader)
    {
        return $this->render('transaction/estimation_header/memo.html.twig', array(
            'estimationHeader' => $estimationHeader,
        ));
    }

    /**
     * @Route("/{id}/add_image", name="transaction_estimation_header_add_image", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TRANSACTION')")
     */
    public function addImageAction(Request $request, EstimationHeader $estimationHeader)
    {
        $estimationHeaderService = $this->get('app.transaction.estimation_header_image');
        $form = $this->createFormBuilder()
            ->add('files', FileType::class, array('multiple' => true, 'constraints' => array(new Count(array('min' => 1)))))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $directory = $this->getParameter('kernel.root_dir') . '/../web/data/images/estimation';
            $estimationHeaderService->save($formData['files'], $estimationHeader, $directory);

            return $this->redirectToRoute('transaction_estimation_header_show', array('id' => $estimationHeader->getId()));
        }

        return $this->render('transaction/estimation_header/add_image.html.twig', array(
            'estimationHeader' => $estimationHeader,
            'form' => $form->createView(),
        ));
    }
}
