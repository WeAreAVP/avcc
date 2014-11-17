<?php

namespace Application\Bundle\FrontBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
//use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Application\Bundle\FrontBundle\Entity\Records;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;

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
		$this->index($args, 'update');
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$this->index($args, 'insert');
	}

	public function index(LifecycleEventArgs $args, $type)
	{
		$entity = $args->getEntity();
		$entityManager = $args->getEntityManager();

                echo '<pre>';print_r($entity);exit;
		if ($entity instanceof Records)
		{
			$sphinxSearch = new SphinxSearch($entityManager, $entity->getId());
			if ($type === 'insert')
				$sphinxSearch->insert();
			else
				$sphinxSearch->update();
		}
	}

}
