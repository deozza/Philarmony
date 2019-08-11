<?php
namespace Deozza\PhilarmonyCoreBundle\Rules;

use Deozza\PhilarmonyCoreBundle\Entity\Entity as MysqlEntity;
use Deozza\PhilarmonyCoreBundle\Document\Entity as MongoEntity;
use Deozza\PhilarmonyCoreBundle\Service\DatabaseSchema\DatabaseSchemaLoader;
use Doctrine\ORM\EntityManagerInterface;

class UniqueConflictRule implements RuleInterface
{
    const ERROR_EXISTS = "PROPERTY_ALREADY_EXISTS";

    public function supports($entity, $posted, $method): bool
    {
        return in_array($method, ['POST', 'PATCH']);
    }

    public function decide($entity, $posted, $method,  $em, DatabaseSchemaLoader $schemaLoader): ?array
    {
        $kind = $entity->getKind();
        $properties = $this->getProperties($schemaLoader, $kind);
        $properties = $this->onlyUniqueProperties($properties);

        $className = $em instanceof EntityManagerInterface ? MysqlEntity::class : MongoEntity::class;

        $submited = $entity->getProperties();
        foreach($properties as $key=>$value)
        {
            if(array_key_exists($key, $posted))
            {
                $exist = $em->getRepository($className)->findAllFiltered(['equal.properties.'.$key=>$submited[$key]], [], $entity->getKind());
                if(count($exist)> 0)
                {
                    return ["conflict" => [$key=>self::ERROR_EXISTS]];
                }
            }
        }
        return null;
    }

    private function getProperties($schemaLoader, $kind)
    {
        $propertiesConfig = $schemaLoader->loadEntityEnumeration($kind)['properties'];

        $properties = [];
        foreach($propertiesConfig as $property)
        {
            $properties[$property] = $schemaLoader->loadPropertyEnumeration($property);
            $type = explode('.', $properties[$property]['type']);
            if($type[0] === "embedded")
            {
                unset($properties[$property]);
                $sub = $schemaLoader->loadEntityEnumeration($type[1])['properties'];
                foreach($sub as $item)
                {
                    $properties[$property.'.'.$item] = $schemaLoader->loadPropertyEnumeration($item);
                }
            }
        }

        return $properties;
    }

    private function onlyUniqueProperties(array $properties)
    {
        foreach($properties as $key=>$value)
        {
            if($value['constraints']['unique'] !== true)
            {
                unset($properties[$key]);
            }
        }

        return $properties;

    }
}