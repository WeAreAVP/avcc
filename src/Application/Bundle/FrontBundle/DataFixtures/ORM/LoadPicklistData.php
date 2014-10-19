<?php

namespace Application\Bundle\FrontBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Bundle\FrontBundle\Entity\MediaTypes;
use Application\Bundle\FrontBundle\Entity\AcidDetectionStrips;
use Application\Bundle\FrontBundle\Entity\Formats;

class LoadUserData implements FixtureInterface
{

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$mediaTypes = array('Audio', 'Video', 'Film');
		foreach ($mediaTypes as $mediaType)
		{
			$mediaTypeObj = new MediaTypes();
			$mediaTypeObj->setName($mediaType);
			$manager->persist($mediaTypeObj);
			$manager->flush();
		}
		$acidDetectionStrips = array(0.0, 0.25, 0.5, 0.75, 1.0, 1.25, 1.5, 1.75, 2.0, 2.25, 2.5, 2.75, 3.0);
		foreach ($acidDetectionStrips as $acidDetectionStrip)
		{
			$acidDetectionStripObj = new AcidDetectionStrips();
			$acidDetectionStripObj->setName($acidDetectionStrip);
			$manager->persist($acidDetectionStripObj);
			$manager->flush();
		}
		$formats = array('1/4 Inch Open Reel Audio', '1/2 Inch Open Reel Audio', '1/2 Inch Open Reel Audio - Digital', '1 Inch Open Reel Audio', '2 Inch Open Reel Audio', '8-Track', 'Cartridge', 'CD - Burnable', 'CD - Pressed', 'Compact Audiocassette', 'DTRS', 'DAT', 'Microcassette', 'Mini-cassette', 'MiniDisc', '45 RPM Disc', '78 RPM Disc', 'LP', 'Lacquer Transcription Disc', 'Other Transcription Disc', '1610/1630 (U-matic)', 'ADAT (VHS)', 'PCM-F1 (Betamax)', 'Wire Recording', 'Cylinder', 'Dictabelt', 'Other Tape Format', 'Other Disc Format');
		foreach ($formats as $format)
		{
			$formatObj = new Formats();
			$formatObj->setName($format);
			$mediaType = $em->getRepository('ApplicationFrontBundle:MediaTypes')->find(1);
			$formatObj->setMediaType($mediaType);
			$manager->persist($formatObj);
			$manager->flush();
		}
	}

}
