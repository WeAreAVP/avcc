<?php

/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */

namespace Application\Bundle\FrontBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Application\Bundle\FrontBundle\SphinxSearch\SphinxSearch;
use Application\Bundle\FrontBundle\Helper\DefaultFields as DefaultFields;

class ChangeSettingsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('avcc:ch_settings')
                ->setDescription('Change order of hidden fields in project settings')
                ->addArgument('id', InputArgument::REQUIRED, ' process id is required')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $id = $input->getArgument('id');
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $entities = $em->getRepository('ApplicationFrontBundle:Projects')->findAll();
        if ($id == 1) {
            foreach ($entities as $entity) {
                $output->writeln("Updateing project Id: " . $entity->getId());
                $dbSettings = $entity->getViewSetting();
                $settings = json_decode($dbSettings, true);
                $new_settings = array();
                if (!empty($settings)) {
                    foreach ($settings as $key => $media) {
                        foreach ($media as $fields) {
                            if ($fields["hidden"] == 0) {
                                $fields["hidden"] = 1;
                            } else {
                                $fields["hidden"] = 0;
                            }
                            $new_settings[$key][] = $fields;
                        }
                    }
                }
                if (!empty($new_settings)) {
                    $entity->setViewSetting(json_encode($new_settings));
                } else {
                    $entity->setViewSetting(NULL);
                }
                $em->persist($entity);
                $em->flush($entity);
            }
        } else if ($id == 2) {
            foreach ($entities as $entity) {
                $output->writeln("Updateing project Id: " . $entity->getId());
                $dbSettings = $entity->getViewSetting();
                $fieldsObj = new DefaultFields();
                if ($dbSettings != NULL && $dbSettings != "") {
                    $defSettings = $fieldsObj->getDefaultOrder();
                    $userViewSettings = $fieldsObj->fields_cmp(json_decode($defSettings, true), json_decode($dbSettings, true));
                    $settings = json_decode($userViewSettings, true);
                    $new_settings = array();
                    foreach ($settings as $key => $setting) {
                        $parent = $this->getParentClassification($setting);
                        foreach ($setting as $field) {
                            if ($field['title'] != "Parent Collection" || $field['title'] != "Collection Classification") {
                                $new_settings[$key][] = $field;
                            }
                            if ($field['title'] == "Title") {
                                $new_settings[$key][] = $parent;
                            }
                        }
                    }
                    if (!empty($new_settings)) {
                        $entity->setViewSetting(json_encode($new_settings));
                        $em->persist($entity);
                        $em->flush($entity);
                    }
                }
            }
        }
        $output->writeln("Done all changes");
        exit;
    }

    private function getParentClassification($array) {
        foreach ($array as $key => $val) {
            if ($val['title'] == "Parent Collection" || $val['title'] == "Collection Classification") {
                if ($val['title'] == "Parent Collection") {
                    $val['title'] = "Collection Classification";
                }
                return $val;
            }
        }
        return null;
    }

}
