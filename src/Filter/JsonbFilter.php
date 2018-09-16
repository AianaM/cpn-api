<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 17.09.2018
 * Time: 0:29
 */

namespace App\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class JsonbFilter extends SQLFilter
{
    private $resourceClass;
    private $property;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if ($targetEntity->getReflectionClass()->name != $this->resourceClass) {
            return '';
        }

        $key = $this->getParameter('key');
        $value = $this->getParameter('value');
        if ($key === '\'any\'') {
            return sprintf('%s.%s::jsonb::text LIKE %s', $targetTableAlias, $this->property, $value);
        }
        return sprintf('%s.%s->>%s LIKE %s', $targetTableAlias, $this->property, $key, $value);
    }

    public function setResource(string $resourceClass, string $property): void
    {
        $this->resourceClass = $resourceClass;
        $this->property = $property;
    }
}