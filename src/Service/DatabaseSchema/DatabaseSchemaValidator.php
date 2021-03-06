<?php

namespace Deozza\PhilarmonyCoreBundle\Service\DatabaseSchema;

use Deozza\PhilarmonyCoreBundle\Exceptions\DataSchemaEmptyOrHeadMissingException;
use Deozza\PhilarmonyCoreBundle\Exceptions\DataSchemaMissingKeyException;
use Deozza\PhilarmonyCoreBundle\Exceptions\DataSchemaUnexpectedKeyException;

class DatabaseSchemaValidator
{
    use ValidateEntityTrait;
    use ValidatePropertyTrait;

    const ENTITY_HEAD = "entities";
    const PROPERTY_HEAD = "properties";
    const ENUM_HEAD = "enumerations";

    const EMPTY_OR_BAD_HEAD_MSG = "The %s schema is empty or does not start with '%s' key.";
    const INVALID_AMOUNT_HEAD_KEY = "The %s schema should contain only 1 head key.";

    public function __construct(DatabaseSchemaLoader $schemaLoader)
    {
        $this->schemaLoader = $schemaLoader;
        $this->entities     = $this->schemaLoader->loadEntityEnumeration();
        $this->properties   = $this->schemaLoader->loadPropertyEnumeration();
        $this->enumerations = $this->schemaLoader->loadEnumerationEnumeration();
    }

    public function validateEntities(): void
    {
        if(empty($this->entities) || !array_key_exists(self::ENTITY_HEAD, $this->entities) || empty($this->entities[self::ENTITY_HEAD]))
        {
            throw new DataSchemaMissingKeyException(sprintf(self::EMPTY_OR_BAD_HEAD_MSG, 'entity', self::ENTITY_HEAD));
        }

        if(count($this->entities) > 1)
        {
            throw new DataSchemaUnexpectedKeyException(sprintf(self::INVALID_AMOUNT_HEAD_KEY, self::ENTITY_HEAD));
        }

        foreach($this->entities[self::ENTITY_HEAD] as $entity=>$entityContent)
        {
            $this->validateEntity($entity, $entityContent);
        }
    }

    public function validateProperties()
    {
        if(empty($this->properties) || !array_key_exists(self::PROPERTY_HEAD, $this->properties))
        {
            throw new DataSchemaMissingKeyException(sprintf(self::EMPTY_OR_BAD_HEAD_MSG, 'property', self::PROPERTY_HEAD));
        }

        if(count($this->properties) > 1)
        {
            throw new DataSchemaUnexpectedKeyException(sprintf(self::INVALID_AMOUNT_HEAD_KEY, self::PROPERTY_HEAD));
        }

        foreach($this->properties[self::PROPERTY_HEAD] as $property=>$propertyContent)
        {
            if(empty($propertyContent))
            {
                throw new DataSchemaEmptyOrHeadMissingException(sprintf(self::INVALID_AMOUNT_HEAD_KEY, self::PROPERTY_HEAD));
            }
            $this->validateProperty($property, $propertyContent);
        }
    }
}