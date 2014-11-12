<?php

namespace Application\Bundle\FrontBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * Class RecordsDatatable
 *
 * @package Application\Bundle\FrontBundle\Datatables
 */
class RecordsDatatable extends AbstractDatatableView
{

    public function __construct()
    {
        $templating = new Template($values);
        $defaultLayoutOptions = array("server_side" => true, "processing" => true, "page_length" => 10, "multiselect" => false, "individual_filtering" => true, "template" => "ApplicationFrontBundle:AudioRecords:index.html.twig");
        parent::__construct($templating, '', '', $defaultLayoutOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->getFeatures()
                ->setAutoWidth(true)
                ->setDeferRender(false)
                ->setInfo(true)
                ->setJQueryUI(false)
                ->setLengthChange(true)
                ->setOrdering(true)
                ->setPaging(true)
                ->setProcessing(true)  // default: false
                ->setScrollX(true)     // default: false
                ->setScrollY("")
                ->setSearching(true)
                ->setServerSide(true)  // default: false
                ->setStateSave(false)
                ->setDelay(500);

        $this->getAjax()->setUrl($this->getRouter()->generate('records_results'));

        $this->setStyle(self::BOOTSTRAP_3_STYLE);

        $this->setIndividualFiltering(true);

        $this->getColumnBuilder()
                ->add('id', 'column', array('title' => 'Id',))
                ->add('createdOn', 'datetime', array('title' => 'CreatedOn',))
//                ->add('updatedOn', 'datetime', array('title' => 'UpdatedOn',))
                ->add('uniqueId', 'column', array('title' => 'UniqueId',))
                ->add('location', 'column', array('title' => 'Location',))
                ->add('title', 'column', array('title' => 'Title',))
                ->add('collectionName', 'column', array('title' => 'CollectionName',))
                ->add('description', 'column', array('title' => 'Description',))
                ->add('contentDuration', 'column', array('title' => 'ContentDuration',))
                ->add('creationDate', 'column', array('title' => 'CreationDate',))
                ->add('contentDate', 'column', array('title' => 'ContentDate',))
//                ->add('isReview', 'boolean', array('title' => 'IsReview',))
//                ->add('genreTerms', 'column', array('title' => 'GenreTerms',))
//                ->add('contributor', 'column', array('title' => 'Contributor',))
//                ->add('generation', 'column', array('title' => 'Generation',))
//                ->add('part', 'column', array('title' => 'Part',))
//                ->add('copyrightRestrictions', 'column', array('title' => 'CopyrightRestrictions',))
//                ->add('duplicatesDerivatives', 'column', array('title' => 'DuplicatesDerivatives',))
//                ->add('relatedMaterial', 'column', array('title' => 'RelatedMaterial',))
//                ->add('conditionNote', 'column', array('title' => 'ConditionNote',))
//                ->add('project.id', 'column', array('title' => 'Project Id',))
                ->add('project.name', 'column', array('title' => 'Project Name',))
//                ->add('project.createdOn', 'column', array('title' => 'Project CreatedOn',))
//                ->add('project.updatedOn', 'column', array('title' => 'Project UpdatedOn',))
//                ->add('user.id', 'column', array('title' => 'User Id',))
                ->add('user.name', 'column', array('title' => 'User Name',))
//                ->add('user.createdOn', 'column', array('title' => 'User CreatedOn',))
//                ->add('user.updatedOn', 'column', array('title' => 'User UpdatedOn',))
//                ->add('mediaType.id', 'column', array('title' => 'MediaType Id',))
                ->add('mediaType.name', 'column', array('title' => 'MediaType Name',))
//                ->add('format.id', 'column', array('title' => 'Format Id',))
                ->add('format.name', 'column', array('title' => 'Format Name',))
//                ->add('commercial.id', 'column', array('title' => 'Commercial Id',))
//                ->add('commercial.name', 'column', array('title' => 'Commercial Name',))
//                ->add('reelDiameters.id', 'column', array('title' => 'ReelDiameters Id',))
//                ->add('reelDiameters.name', 'column', array('title' => 'ReelDiameters Name',))
//                ->add('audioRecord.id', 'column', array('title' => 'AudioRecord Id',))
//                ->add('audioRecord.mediaDuration', 'column', array('title' => 'AudioRecord MediaDuration',))
//                ->add('videoRecord.id', 'column', array('title' => 'VideoRecord Id',))
//                ->add('videoRecord.mediaDuration', 'column', array('title' => 'VideoRecord MediaDuration',))
//                ->add('filmRecord.id', 'column', array('title' => 'FilmRecord Id',))
//                ->add('filmRecord.footage', 'column', array('title' => 'FilmRecord Footage',))
//                ->add('filmRecord.mediaDiameter', 'column', array('title' => 'FilmRecord MediaDiameter',))
//                ->add('filmRecord.shrinkage', 'column', array('title' => 'FilmRecord Shrinkage',))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'Application\Bundle\FrontBundle\Entity\Records';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'records_datatable';
    }

}
