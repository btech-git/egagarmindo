<?php

namespace AppBundle\Grid\Common;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectRepository;
use LibBundle\Grid\DataGridType;
use LibBundle\Grid\WidgetsBuilder;
use LibBundle\Grid\DataBuilder;
use LibBundle\Grid\SortOperator\BlankType as SortBlankType;
use LibBundle\Grid\SortOperator\AscendingType;
use LibBundle\Grid\SortOperator\DescendingType;
use LibBundle\Grid\SearchOperator\EqualNonEmptyType;
use LibBundle\Grid\SearchOperator\ContainNonEmptyType;
use LibBundle\Grid\Transformer\EntityTransformer;
use AppBundle\Entity\Master\Unit;
use AppBundle\Entity\Master\MaterialCategory;
use AppBundle\Entity\Master\Material;

class MaterialGridType extends DataGridType
{
    /**
     * @param WidgetsBuilder $builder
     * @param array $options
     */
    public function buildWidgets(WidgetsBuilder $builder, array $options)
    {
        $em = $options['em'];
        $materialCategories = $em->getRepository(MaterialCategory::class)->findAll();
        $materialCategoryLabelModifier = function($materialCategory) { return $materialCategory->getName(); };
        $units = $em->getRepository(Unit::class)->findAll();
        $unitLabelModifier = function($unit) { return $unit->getName(); };

        $builder->searchWidget()
            ->addGroup('material')
                ->setEntityName(Material::class)
                ->addField('name')
                    ->addOperator(ContainNonEmptyType::class)
                ->addField('materialCategory')
                    ->setDataTransformer(new EntityTransformer($em, MaterialCategory::class))
                    ->addOperator(EqualNonEmptyType::class)
                        ->getInput(1)
                            ->setListData($materialCategories, $materialCategoryLabelModifier)
                ->addField('unit')
                    ->setDataTransformer(new EntityTransformer($em, Unit::class))
                    ->addOperator(EqualNonEmptyType::class)
                        ->getInput(1)
                            ->setListData($units, $unitLabelModifier)
        ;

        $builder->sortWidget()
            ->addGroup('material')
                ->addField('name')
                    ->addOperator(SortBlankType::class)
                    ->addOperator(AscendingType::class)
                    ->addOperator(DescendingType::class)
        ;

        $builder->pageWidget()
            ->addPageSizeField()
                ->addItems(10, 25, 50, 100)
            ->addPageNumField()
        ;
    }

    /**
     * @param DataBuilder $builder
     * @param ObjectRepository $repository
     * @param array $options
     */
    public function buildData(DataBuilder $builder, ObjectRepository $repository, array $options)
    {
        $criteria = Criteria::create();

        $builder->processSearch(function($values, $operator, $field) use ($criteria) {
            $operator::search($criteria, $field, $values);
        });

        $builder->processSort(function($operator, $field) use ($criteria) {
            $operator::sort($criteria, $field);
        });

        $builder->processPage($repository->count($criteria), function($offset, $size) use ($criteria) {
            $criteria->setMaxResults($size);
            $criteria->setFirstResult($offset);
        });
        
        $objects = $repository->match($criteria);

        $builder->setData($objects);
    }
}
