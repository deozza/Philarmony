<?php
namespace Deozza\PhilarmonyCoreBundle\Service\DatabaseSchema;

use Deozza\PhilarmonyUtils\DataSchema\AuthorizedKeys;
use Deozza\PhilarmonyUtils\Exceptions\FileNotFound;
use Symfony\Component\Yaml\Yaml;

class DatabaseSchemaLoader
{

    public function __construct(string $entity, string $property, string $enumeration, string $path)
    {
        $this->entityPath = $entity;
        $this->propertyPath = $property;
        $this->enumerationPath = $enumeration;
        $this->rootPath = $path;
    }

    public function loadEntityEnumeration($entity_name = null, $returnKey = false)
    {
        $entities = file_get_contents($this->rootPath.$this->entityPath.".yaml");

        try
        {
            $values = Yaml::parse($entities);
        }
        catch(\Exception $e)
        {
            throw new FileNotFound($e->getMessage());
        }

        if(empty($entity_name))
        {
            return $values;
        }

        foreach (array_keys($values[AuthorizedKeys::ENTITY_HEAD]) as $key)
        {
            if($key == $entity_name)
            {
                if($returnKey)
                {
                    return $key;
                }
                return $values[AuthorizedKeys::ENTITY_HEAD][$key];
            }
        }

        return null;
    }

    public function loadPropertyEnumeration($property_name = null, $returnKey = false)
    {
        $properties = file_get_contents($this->rootPath.$this->propertyPath.".yaml");

        try
        {
            $values = Yaml::parse($properties);
        }
        catch(\Exception $e)
        {
            throw new FileNotFound($e->getMessage());
        }

        if(empty($property_name))
        {
            return $values;
        }

        foreach (array_keys($values[AuthorizedKeys::PROPERTY_HEAD]) as $key)
        {
            if($key == $property_name)
            {
                if($returnKey)
                {
                    return $key;
                }
                return $values[AuthorizedKeys::PROPERTY_HEAD][$key];
            }
        }

        return null;
    }

    public function loadEnumerationEnumeration($enumeration_name = null, $returnKey = false)
    {
        $enumerations = file_get_contents($this->rootPath . $this->enumerationPath . ".yaml");

        try
        {
            $values = Yaml::parse($enumerations);
        }
        catch (\Exception $e)
        {
            throw new FileNotFound($e->getMessage());
        }

        if (empty($enumeration_name)) {
            return $values;
        }

        foreach (array_keys($values[AuthorizedKeys::ENUMERATION_HEAD]) as $key) {
            if ($key == $enumeration_name) {
                if ($returnKey) {
                    return $key;
                }
                return $values[AuthorizedKeys::ENUMERATION_HEAD][$key];
            }
        }

        return null;
    }

    public function propertyFinder(string $propertyName, string $entityName, int $i=0)
    {
        $entities = $this->loadEntityEnumeration($entityName);
        $explodedPropertyName = explode(".", $propertyName);
        if(count($explodedPropertyName)>1)
        {
            return $this->propertyFinder($explodedPropertyName[$i+2], $explodedPropertyName[$i], 2);
        }

        $availableProperties = $this->loadEntityEnumeration($entityName)[AuthorizedKeys::ENTITY_KEYS[0]];
        if(empty($availableProperties))
        {
            return false;
        }
        return in_array($explodedPropertyName[0], $availableProperties);
    }
}