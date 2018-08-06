<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Document
    {
        private $nid = null;
        private $node = null;
        private $docs = null;
        
        public function __construct($nid = null, $node = null)
        {
            $this->nid = $nid;
            $this->node = $node;
            
        }
        
        public function getDocumentInfo()
        {
            kint_require();
            \Kint::$maxLevels = 5;
            
            $docs = array();
            foreach( $this->node->get('field_matter_documents')->getValue() as $k1 => $v1){
                //kint($k1);
                //kint($v1);
                $file = \Drupal\file\Entity\File::load($v1['target_id']);
                //kint($file);
                $fileName = $file->getFilename();
                //kint($fileName);
                $fileUri = $file->getFileUri();
                //kint($fileUri);
                $fileUrl = file_create_url($fileUri);
                //kint($fileUrl);
                $size = $file->getSize();
                //kint($size);
                $size2 = $this->formatBytesSize($size);
                //kint($size2);
                $docs[] = array('fileName' => $fileName, 'fileUrl' => $fileUrl, 'size' => $size2);
            }
            $this->docs = $docs;
            return $docs;
            
        }
        
        //List each document in the node
        public function renderDocumentsList()
        {
            kint_require();
            \Kint::$maxLevels = 5;
            
            $docs = $this->getDocumentInfo();
            //kint($docs);
            
            $ret = '';
            foreach($docs as $k1 => $v1){
            
                $ret .= '<div class="lc-list-matters-item">';

                    $ret .= '<div class="lc-list-matters-item-inner">';

                        $ret .= '<div class="lc-list-quotes-item-left">';

                            ///$editLink = "";
                            ///if($this->canEditQuote()){
                            ///    $editLink = '<a href="/node/'.$this->node->id().'/edit" class="content-list-edit-link">edit</a>';
                            ///}

                            //matter title
                            $ret .= '<div class="lc-list-matters-item-title">';
                                //$ret .= '<a href="/quote/'.$this->node->id().'">'.$this->node->getTitle().'</a> - '.$editLink;
                                //$ret .= '<a href="/quote/'.$this->node->id().'">'.$this->node->getTitle().'</a>';
                                $ret .= '<a href="'.$v1['fileUrl'].'" target="_blank">'.$v1['fileName'].'</a>'; 
                            $ret .= '</div>';

                            //quote id
                            /////$matterID = $this->node->get('field_lc_matter_id')->getString();
                            //kint($matterID); 
                            $ret .= '<div class="lc-list-matters-item-id">';
                                $ret .= 'File size: '.$v1['size'];
                            $ret .= '</div>';

                            //quote date
                            /*
                            $ret .= '<div class="lc-list-matters-item-client">';
                                $ret .= '<span class="lc-list-matters-item-client-bookmark"><i class="icon-calendar-1"></i></span><span class="lc-list-matters-item-client-name">'.'23 Mar, 2017???'.'</span>';
                            $ret .= '</div>';

                            //quote amount pending
                            $ret .= '<div class="lc-list-matters-item-doc-count">';
                                $ret .= '<span class="lc-list-matters-item-doc-doc-icon"><i class="icon-calc"></i></span><span class="lc-list-matters-item-doc-doc-count">R 250 Pending</span>';
                            $ret .= '</div>';

                            //quote if pending
                            $ret .= '<div class="lc-matter-list-quote-pending">';
                                $ret .= '<span class="lc-list-matters-item-doc-doc-icon"><i class="icon-clock"></i></span><span class="lc-list-matters-item-doc-doc-count">Pending</span>';
                            $ret .= '</div>';
                            */

                        $ret .= '</div>';

                        /////$ret .= '<div class="lc-list-matters-item-right">';

                            /////$ret .= //'<span class="lc-list-matters-item-right-star"><a href="#"><i class="icon-star-empty-1"></i></a></span>'.
                                    /////'<span class="lc-list-matters-item-right-open"><a href="/quote/'.$this->node->id().'"><i class="icon-right-open"></i></a></span>';

                        /////$ret .= '</div>';

                    $ret .= '</div>';

                $ret .= '</div>';
                
            }
            
            return $ret;
        }
        
        public function formatBytesSize($bytes, $precision = 2)
        {
            $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

            $bytes = max($bytes, 0); 
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
            $pow = min($pow, count($units) - 1); 

            // Uncomment one of the following alternatives
            $bytes /= pow(1024, $pow);
            //$bytes /= (1 << (10 * $pow)); 

            return round($bytes, $precision) . ' ' . $units[$pow];
        }
        
        public function formatBytesSize2($bytes, $precision = 2)
        {
            // Format string
            $format = ($format === NULL) ? '%01.2f %s' : (string) $format;

            // IEC prefixes (binary)
            if ($si == FALSE OR strpos($force_unit, 'i') !== FALSE)
            {
                $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
                $mod   = 1024;
            }
            // SI prefixes (decimal)
            else
            {
                $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
                $mod   = 1000;
            }

            // Determine unit to use
            if (($power = array_search((string) $force_unit, $units)) === FALSE)
            {
                $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
            }

            return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
        }
        
        
    }