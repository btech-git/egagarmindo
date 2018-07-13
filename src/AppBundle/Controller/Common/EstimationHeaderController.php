<?php

namespace AppBundle\Controller\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\EstimationHeader;
use AppBundle\Grid\Common\EstimationHeaderGridType;

/**
 * @Route("/common/estimation_header")
 */
class EstimationHeaderController extends Controller
{
    /**
     * @Route("/grid", name="common_estimation_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(EstimationHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(EstimationHeaderGridType::class, $repository, $request);

        return $this->render('common/estimation_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }
}
