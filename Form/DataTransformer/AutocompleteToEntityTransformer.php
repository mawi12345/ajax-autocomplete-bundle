<?php

namespace Mawi\AjaxAutocompleteBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AutocompleteToEntityTransformer implements DataTransformerInterface
{

    protected $em;
    protected $unitOfWork;
    protected $config;
    protected $stripTags = false;

    public function __construct(EntityManager $em, $config)
    {
        $this->em = $em;
        $this->unitOfWork = $this->em->getUnitOfWork();
        $this->config = $config;
    }
    
    public function setStripTags($value)
    {
    	$this->stripTags = $value;
    }

    public function transform($value)
    {
    	if ($value === null) return $value;
    	
    	if (!is_object($value)) {
    		throw new UnexpectedTypeException($entity, 'object');
    	}
    	if (!$this->unitOfWork->isInIdentityMap($value)) {
    		throw new FormException('Entities passed to the choice field must be managed');
    	}
    	
    	$labelGetter = 'get'.ucfirst($this->config['label']);
    	
    	$transformed = array('id' => $value->getId(), 'label' => $value->$labelGetter());
    	
    	if (array_key_exists('labelClass', $this->config) && strlen($this->config['labelClass'])>1) {
    		$labelClassGetter = 'get'.ucfirst($this->config['labelClass']);
    		$transformed['clazz'] = $value->$labelClassGetter();
    	}
    	
    	if ($this->stripTags) {
    		$transformed['label'] = strip_tags($transformed['label']);
    	} 
    	
    	return $transformed;
    }

    public function reverseTransform($value)
    {
    	if ($value === null || !is_array($value) || !array_key_exists('id', $value) || !array_key_exists('label', $value)) return $value;
    	        
    	if (!is_numeric($value['id']))
    	{
    		throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $value['id']));
    	}
    	    	
    	$repository = $this->em->getRepository($this->config['class']);
    	    	
    	$entity = $repository->find(intval($value['id']));
    	
    	if ($entity === null) {
    		throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $value['id']));
    	}
    	
    	return $entity;
    }
}