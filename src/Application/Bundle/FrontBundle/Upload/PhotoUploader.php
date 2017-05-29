<?php

namespace Application\Bundle\FrontBundle\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Application\Bundle\FrontBundle\Entity\RecordImages;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Gaufrette\Filesystem;

class PhotoUploader {

    private static $allowedMimeTypes = array(
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/bmp',
        'image/vnd.microsoft.icon',
        'image/tiff',
        'image/svg+xml'
    );
    private $filesystem;
    private $entityManager;
    private $container;

    public function __construct(Filesystem $fs, ContainerInterface $container) {
        $this->filesystem = $fs;
        $this->container = $container;
        $this->entityManager = $container->get('doctrine')->getEntityManager();
    }

    public function upload($files, $recordID) {
        foreach ($files as $key => $file) {
            $imageRec = new RecordImages();
// Check if the file's mime type is in the list of allowed mime types.
//            echo $file->getClientMimeType();exit;
            if (!in_array($file->getClientMimeType(), self::$allowedMimeTypes)) {
                throw new \InvalidArgumentException(sprintf('Files of type %s are not allowed.', $file->getClientMimeType()));
            }
// Generate a unique filename based on the date and add file extension of the uploaded file
//            $file->get
            $filename = sprintf('%s/%s_%s_%s', $recordID, $recordID, strtotime("now"), $file->getClientOriginalName());

            $adapter = $this->filesystem->getAdapter();
            $adapter->setMetadata($filename, array('contentType' => $file->getClientMimeType()));
            $adapter->write($filename, file_get_contents($file->getPathname()));
            $imageRec->setAwsPath($this->container->getParameter("amazon_s3_base_url") . $filename);
            $imageRec->setFilename($filename);
            $imageRec->setRecordId($recordID);
            $this->entityManager->persist($imageRec);
            $this->entityManager->flush($imageRec);
        }
        $this->entityManager->flush();
        return "";
    }

}
