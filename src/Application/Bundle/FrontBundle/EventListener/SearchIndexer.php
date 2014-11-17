<?php

namespace Application\Bundle\FrontBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\Bundle\FrontBundle\Entity\Records;
use Application\Bundle\FrontBundle\Entity\AudioRecords;
use Application\Bundle\FrontBundle\Entity\FilmRecords;
use Application\Bundle\FrontBundle\Entity\VideoRecords;

class SearchIndexer
{

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();

		// perhaps you only want to act on some "Product" entity
		if ($entity instanceof AudioRecords || $entity instanceof VideoRecords || $entity instanceof FilmRecords)
		{
			echo 'there';exit;
			// ... do something with the Product
		}
	}

}
