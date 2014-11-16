<?php

namespace Application\Bundle\FrontBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
//use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Application\Bundle\FrontBundle\Entity\Records;

class SearchIndexerSubscriber implements EventSubscriber
{

	public function getSubscribedEvents()
	{
		return array(
			'postPersist',
			'postUpdate',
		);
	}

	public function postUpdate(LifecycleEventArgs $args)
	{
		$this->index($args);
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$this->index($args);
	}

	public function index(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();

		// perhaps you only want to act on some "Product" entity
		if ($entity instanceof Records)
		{
			echo 'here';exit;
			// ... do something with the Product
		}
	}

}
