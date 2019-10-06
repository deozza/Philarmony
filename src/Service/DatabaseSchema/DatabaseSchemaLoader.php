<?php
namespace Deozza\PhilarmonyCoreBundle\Service\DatabaseSchema;

use Symfony\Component\Yaml\Yaml;

class DatabaseSchemaLoader
{

    public function __construct(string $entity, string $property, string $enumeration, string $path)
    {
        $this->entityPath = $entity;
        $this->propertyPath = $property;
        $this->enumerationPath = $enumeration;
        $this->rootPath = $path;
        $this->authorizedKeys = Yaml::parse(file_get_contents(__DIR__.'/authorizedKeys.yaml'));
    }

    public function loadEntityEnumeration($entity_name = null)
    {
        $entities = file_get_contents($this->rootPath.$this->entityPath.".yaml");

        try
        {
            $values = Yaml::parse($entities);
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        if(empty($entity_name))
        {
            return $values;
        }

        foreach (array_keys($values[$this->authorizedKeys['entity_head']]) as $key)
        {
            if($key == $entity_name)
            {
                return $values[$this->authorizedKeys['entity_head']][$key];
            }
        }

        return null;
    }

    public function loadPropertyEnumeration($property_name = null)
    {
        $properties = file_get_contents($this->rootPath.$this->propertyPath.".yaml");

        try
        {
            $values = Yaml::parse($properties);
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        if(empty($property_name))
        {
            return $values;
        }

        foreach (array_keys($values[$this->authorizedKeys['property_head']]) as $key)
        {
            if($key == $property_name)
            {
                return $values[$this->authorizedKeys['property_head']][$key];
            }
        }

        return null;
    }

    public function loadEnumerationEnumeration($enumeration_name = null)
    {
        $enumerations = file_get_contents($this->rootPath . $this->enumerationPath . ".yaml");

        try
        {
            $values = Yaml::parse($enumerations);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        if (empty($enumeration_name)) {
            return $values;
        }

        foreach (array_keys($values[$this->authorizedKeys['enumeration_head']]) as $key) {
            if ($key == $enumeration_name)
            {
                return $values[$this->authorizedKeys['enumeration_head']][$key];
            }
        }

        return null;
    }

    public function propertyFinder(string $propertyName, string $entityName, int $i=0)
    {
        $explodedPropertyName = explode(".", $propertyName);
        if(count($explodedPropertyName)>1)
        {
            return $this->propertyFinder($explodedPropertyName[$i+2], $explodedPropertyName[$i], 2);
        }

        $availableProperties = $this->loadEntityEnumeration($entityName)[$this->authorizedKeys['entity_keys'][0]];
        if(empty($availableProperties))
        {
            return false;
        }
        return in_array($explodedPropertyName[0], $availableProperties);
    }
}