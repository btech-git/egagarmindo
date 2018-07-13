<?php

namespace AppBundle\Controller\Common;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Master\Material;
use AppBundle\Grid\Common\MaterialGridType;

/**
 * @Route("/common/material")
 */
class MaterialController extends Controller
{
    /**
     * @Route("/grid", name="common_material_grid", condition="request.isXmlHttpRequest()")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function gridAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Material::class);

        $grid = $this->get('lib.grid.datagrid');
        $grid->build(MaterialGridType::class, $repository, $request, array('em' => $em));

        return $this->render('common/material/grid.html.twig', array(
            'grid' => $grid->createView(),
        ));
    }
}
