<?php

namespace AppBundle\Controller\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Transaction\SampleRequestHeader;
use AppBundle\Grid\Common\SampleRequestHeaderGridType;

/**
 * @Route("/common/sample_request_header")
 */
class SampleRequestHeaderController extends Controller
{
    /**
     * @Route("/grid", name="common_sample_request_header_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(SampleRequestHeader::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(SampleRequestHeaderGridType::class, $repository, $request);

        return $this->render('common/sample_request_header/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }
}
