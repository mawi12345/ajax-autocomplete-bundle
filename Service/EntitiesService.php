<?php
namespace Mawi\AjaxAutocompleteBundle\Service;
use Symfony\Component\DependencyInjection\ContainerAware;

class EntitiesService extends ContainerAware implements
		EntitiesServiceInterface {
	
	public function getEntities($term, array $conf) {
		
		switch ($conf['search']){
			case "begins_with":
				$like = $term . '%';
				break;

			case "ends_with":
				$like = '%' . $term;
				break;

			case "contains":
				$like = '%' . $term . '%';
				break;

			default:
				throw new \Exception('Unexpected value of parameter "search"');
		}
		
		$em = $this->container->get('doctrine')->getManager();

		$results = $em->createQuery($conf['query'])
			->setParameter('term', $like )
			->setMaxResults($conf['max'])
			->getResult();
		
		return $results;
	}

}
