<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 14.09.2018
 * Time: 14:25
 */

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

final class AndPartialFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass)
        ) {
            return;
        }

        $parameterName = $queryNameGenerator->generateParameterName($property);
        $i=0;
        foreach ($value as $role) {
            $queryBuilder
                ->andWhere(sprintf('o.%s LIKE :%s', $property, $parameterName.$i))
                ->setParameter($parameterName.$i, '%' . $role . '%');
            $i++;
        }
    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description["andPartial_$property"] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'partial AND filter',
                    'name' => $property."[]",
                    'type' => 'string',
                ],
            ];
        }

        return $description;
    }
}