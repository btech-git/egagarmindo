<?php

namespace AppBundle\Controller\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\Quotation;
use AppBundle\Grid\Common\QuotationGridType;

/**
 * @Route("/common/quotation")
 */
class QuotationController extends Controller
{
    /**
     * @Route("/grid", name="common_quotation_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Quotation::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(QuotationGridType::class, $repository, $request);

        return $this->render('common/quotation/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }
}
