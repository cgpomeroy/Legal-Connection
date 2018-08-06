<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Documents
    {
        public $matter;
        public $documents = array();
        
        public function __construct($matter = null)
        {
            $this->matter = $matter;
        }
        
        public function matterDocumentsDisplay()
        {
            //$res = '<div class="lc-matter-document-content-wrap">';
            
            //    $res .= '<a href="/node/add/documents?matter='.$this->matter->nid.'&destination=matter/'.$this->matter->nid.'" class="lc-button">Add Documents</a>';
            
            //$res .= '</div>';
            
            $nids = $this->getMatterDocumentIDs();
            $this->instantiateDocuments($nids, true);
            $res .= $this->renderDocumentList();
            
            /*
            $nids = $this->getMatterDocumentIDs();
            $this->instantiateDocuments($nids, true);
            
            $res .= $this->renderDocumentList();
            */
            
            
            
            
            
            
            return $res;
        }
        
        //Get links data for the context menu
        public function getContextMenuData()
        {
            //$menuData = array(
            //    array('iconClass' => 'icon-upload', 'linkTitle' => 'File Upload', 'url' => '/node/add/documents?matter=69&destination=matter/69'),
                
            //);
            
            $link1 = new \stdClass();
            $link1->iconClass = 'icon-upload';
            $link1->linkTitle = 'Upload file';
            $link1->url = '/node/add/documents?matter=69&destination=matter/69';
            
            return array($link1);
            
            
        }
        
        //query database for quotes ids attached to a matter
        public function getMatterDocumentIDs()
        {
            $connection = \Drupal::database();
            $q = $connection->query("SELECT n.nid FROM {node_field_data} n JOIN {node__field_matter} fm ON n.nid = fm.entity_id ".
                    "WHERE n.type = :type AND fm.field_matter_target_id = :mid ORDER BY n.nid DESC", 
                    array(':type' => 'documents', ':mid' => $this->matter->nid));
            $records = $q->fetchAll();
            
            $nids = array();
            foreach ($records as $record) {
                $nids[] = $record->nid;
            }
                
            return $nids;
            
            
            
            //\Drupal\debugsave\Debugsave::debug($this->quotes);
            
            
        }
        
        //create quotes objects with nodes or nids
        private function instantiateDocuments($nids, $loadNodes = null)
        {
            if($loadNodes != null){
                
                $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
                // \Drupal\debugsave\Debugsave::debug($nodes);
                foreach($nodes as $k1 => $v1){
                    $this->documents[] = new Custom\Document($k1, $v1);
                }
            }
            else {
                foreach($nids as $k1 => $v1){
                    $this->documents[] = new Custom\Document($v1, null);
                }
            }
        }
        
        
        private function renderDocumentList()
        {
            $res = '<div class="lc-list-matters lc-list-all-matters lc-matter-list-documents">';
            foreach($this->documents as $k1 => $v1){
                $res .= $v1->renderDocumentsList();
            }
            $res .= '</div>';
            return $res;
        }
        
        
        
    }