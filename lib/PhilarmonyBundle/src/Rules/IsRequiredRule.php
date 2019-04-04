<?php
namespace Deozza\PhilarmonyBundle\Rules;

use Deozza\PhilarmonyBundle\Service\DatabaseSchemaLoader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class IsRequiredRule
{
    const ERROR_IS_REQUIRED = "PROPERTY_IS_REQUIRED";

    public function supports($context, Request $request)
    {
        return in_array($request->getMethod(), ['DELETE']);
    }

    public function decide($object,Request $request, EntityManagerInterface $em, DatabaseSchemaLoader $schemaLoader)
    {
        $propertyKind = $schemaLoader->loadPropertyEnumeration($object->getKind());


        if($propertyKind['IS_REQUIRED'] == true)
        {
            return ["conflict" => self::ERROR_IS_REQUIRED];
        }

        return ;

    }
}