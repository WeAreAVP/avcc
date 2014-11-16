<?php

namespace Application\Bundle\FrontBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\Bundle\FrontBundle\Entity\Records;

class SearchIndexer
{

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();
echo 'there';exit;
		// perhaps you only want to act on some "Product" entity
		if ($entity instanceof Records)
		{
			
			// ... do something with the Product
		}
	}

}
