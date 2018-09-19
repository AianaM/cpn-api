<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 17.09.2018
 * Time: 0:50
 */

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

final class SearchJsonbFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass)
        ) {
            return;
        }

        foreach ($value as $key => $val) {
            $filter = $queryBuilder->getEntityManager()->getFilters()->enable('jsonb_filter');
            if (is_array($val)) {
                $filter->setParameter('key', '{' . $key . '}');
                $filter->setParameter('array', '["' . join('","', $val) . '"]');
            } else {
                $filter->setParameter('key', $key);
                $filter->setParameter('value', '%' . $val . '%');
            }
//            $check = explode('.', $property);
//            if (count($check) > 1) {
//                $filter->setResource($check[0], $check[1]);
//            } else {
//                $filter->setResource($resourceClass, $property);
//            }
                $filter->setResource($resourceClass, $property);
        }
    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["jsonb_$property"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter using a regex. This will appear in the Swagger documentation!',
                    'name' => 'Custom name to use in the Swagger documentation',
                    'type' => 'Will appear below the name in the Swagger documentation',
                ],
            ];
        }

        return $description;
    }
}