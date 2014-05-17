<?php

namespace Mawi\AjaxAutocompleteBundle\Service;

use Mawi\AjaxAutocompleteBundle\Form\Type\AjaxAutocompleteType;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerAware;
use Mawi\AjaxAutocompleteBundle\Form\DataTransformer\AutocompleteToEntityTransformer;

class AjaxAutocomplete extends ContainerAware
{
	private function getConfig($alias)
	{
		$entities = $this->container->get('service_container')->getParameter('mawi.autocomplete');
		return $entities[$alias];
	}
	
    public function listAction()
    {
        $em = $this->container->get('doctrine')->getManager();
        $request = $this->container->get('request');

        $alias = $request->get('alias');
        $config = $this->getConfig($alias);

        if (false === $this->container->get('security.context')->isGranted( $config['role'] )) {
            throw new AccessDeniedException();
        }

        $term = $request->get('term');
        
        $results = $this->container->get( $config['service'] )->getEntities($term, $config);
        
        $transformer = new AutocompleteToEntityTransformer(
        		$this->container->get('doctrine')->getManager(),
        		$config
        );
        
        $res = array();
        foreach ($results as $entity) {
        	$res[] = $transformer->transform($entity);
        }

        return new Response(json_encode($res));
    }
}
