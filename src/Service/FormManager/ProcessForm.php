<?php
namespace Deozza\PhilarmonyCoreBundle\Service\FormManager;

use Deozza\PhilarmonyUtils\Exceptions\BadFileTree;
use Deozza\PhilarmonyCoreBundle\Service\DatabaseSchema\DatabaseSchemaLoader;
use Deozza\ResponseMakerBundle\Service\FormErrorSerializer;
use Deozza\ResponseMakerBundle\Service\ResponseMaker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProcessForm
{
    use AddFieldTrait;
    use SaveDataTrait;

    public function __construct(ResponseMaker $responseMaker, FormErrorSerializer $serializer, FormFactoryInterface $formFactory, DatabaseSchemaLoader $schemaLoader, EntityManagerInterface $em)
    {
        $this->response = $responseMaker;
        $this->serializer = $serializer;
        $this->form = $formFactory;
        $this->schemaLoader = $schemaLoader;
        $this->em = $em;
    }

    public function generateAndProcess($formKind, $requestBody, $entityToProcess, $entityKind, $formFields)
    {
        $this->user = $entityToProcess->getOwner();
        if(!is_object($entityToProcess))
        {
            return;
        }

        $form = $this->form->create(FormType::class, null, ['csrf_protection' => false]);

        if($formFields === "all")
        {
            $formFields = $entityKind['properties'];
        }
        try
        {
            $this->formFields = $this->selectFormFields($formFields);
        }
        catch(\Exception $e)
        {
            return $this->response->badRequest($e->getMessage());
        }
        if(!is_object(json_decode($requestBody)))
        {
            $data = $this->saveData($requestBody, $entityToProcess, $formKind, $formFields);
            return $data;
        }


        foreach($this->formFields as $field=>$config)
        {
            if(!isset($config['constraints']['automatic']))
            {
                $this->addFieldToForm($field, $config, $form);
            }
        }

        $data = $this->processData($requestBody, $form, $formKind);


        if(is_a($data, JsonResponse::class))
        {
            return $data;
        }

        if(!is_object($data))
        {
            $this->saveData($data, $entityToProcess, $formKind, $this->formFields);
        }

        return $data;
    }

    private function selectFormFields(array $properties, $fields = [], $isRequired = null, $arrayOf = null)
    {
        foreach($properties as $property)
        {
            try
            {
                $propertyConfig = $this->schemaLoader->loadPropertyEnumeration($property);
            }
            catch(\Exception $e)
            {
                return $this->response->badRequest($e->getMessage());
            }

            $type = explode('.',$propertyConfig['type']);

            if(in_array("embedded", $type))
            {
                try
                {
                    $embeddedPropertyConfig = $this->schemaLoader->loadEntityEnumeration($type[1])['properties'];
                }
                catch(\Exception $e)
                {
                    return $this->response->badRequest($e->getMessage());
                }

                if(isset($propertyConfig['array']))
                {
                    $arrayOf = $property;
                }

                $isRequired = ($propertyConfig['constraints']['required'] === false) ? false : null;
                $fields = array_merge($fields, $this->selectFormFields($embeddedPropertyConfig, $fields, $isRequired, $arrayOf));
            }
            else
            {
                $realType = $type[0];
                $isArray = false;
                if($realType === "enumeration" || $realType === "entity")
                {
                    $realType .= ".".$type[1];
                }
                if($isRequired !== null)
                {
                    $propertyConfig['constraints']['required'] = $isRequired;
                }

                if(isset($propertyConfig['array']))
                {
                    $isArray = $propertyConfig['array'];
                }

                $fields[$property] = ["type"=>$realType, "array"=>$isArray, "constraints"=>$propertyConfig['constraints'], "arrayOf"=>$arrayOf];
            }
        }
        return $fields;
    }

    private function formatData($data, $formFields)
    {
        foreach($formFields as $field)
        {
            try
            {
                $config = $this->schemaLoader->loadPropertyEnumeration($field);
            }
            catch(\Exception $e)
            {
                return $this->response->badRequest($e->getMessage());
            }

            $isEmbedded = explode("embedded.", $config['type']);
            if(count($isEmbedded)>1)
            {
                try
                {
                    $embeddedProperties = $this->schemaLoader->loadEntityEnumeration($isEmbedded[1])['properties'];
                }
                catch(\Exception $e)
                {
                    return $this->response->badRequest($e->getMessage());
                }

                foreach($embeddedProperties as $property)
                {
                    if(isset($data[$property]))
                    {
                        $data[$isEmbedded[1]][$property] = $data[$property];
                        unset($data[$property]);
                    }
                }
            }
        }
        return $data;
    }

    private function cleanData($datas)
    {
        foreach ($datas as $key=>$data)
        {
            if(empty($data))
            {
                unset($datas[$key]);
            }
        }
        return $datas;
    }

    private function processData($data, $form, $formKind)
    {
        $data = json_decode($data, true);

        $form->submit($data, $formKind==="post");

        if(!$form->isValid())
        {
            return $this->response->badRequest($this->serializer->convertFormToArray($form));
        }

        return $form->getData();
    }
}