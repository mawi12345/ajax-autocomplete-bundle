<?php
namespace Mawi\AjaxAutocompleteBundle\Service;

use Symfony\Component\HttpFoundation\Request;

interface EntitiesServiceInterface {
	
	public function getEntities($term, array $conf);
	
}
