<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class MatterHeader extends TitleHeader
    {
        protected $matter;
        
        public function __construct($matter, $showBackLink = null, $backUri = null)
        {
            parent::__construct($showBackLink, $backUri);
            $this->matter = $matter;
        }
        
        public function render()
        {
            $this->renderItems[] = $this->getBack();
            $this->renderItems[] = $this->renderHeaderMatterIcon();
            
            $title = $this->getTitle($this->matter->getTitle());
            $people = $this->getMatterPeople();
            $titleHeader = '<div class="lc-matter-th-title-section">'.
                                '<div class="lc-matter-th-title-title">'.$title.'</div>'.
                                '<div class="lc-matter-th-title-tap"><a href="#">Tap here for group info</a></div>'.
                                $people.
                            '</div>';
            $this->renderItems[] = $titleHeader;
            
            return $this->renderItems();
        }
    
        private function renderHeaderMatterIcon()
        {
            $matterIcon = $this->matter->getMatterDisplayIcon();
            //kint($matterIcon);
            return $matterIcon;
        }
        
        private function getMatterPeople()
        {
            $people = $this->matter->getMatterPeople();
            return $this->renderMatterPeople($people);
        }
        
        private function renderMatterPeople($people)
        {
            $res = '<div class="lc-matter-th-people">';
            
                $res .= $this->renderMatterPerson($people['client'][0]);
                
                foreach($people['lawyers'] as $k => $v){
                    $res .= $this->renderMatterPerson($v);
                }
            
            $res .= '</div>';
            return $res;
        }
        private function renderMatterPerson($person)
        {
            //kint($person);
            //kint($person['pic']);
            
            
            $res = '<div class="lc-matter-th-person" data-matter-person="'.$person['uid'].'">';
            
                if($person['pic'] !== 0){
            
                    $res .= '<div class="lc-matter-th-person-pic lc-matter-th-person-pic-color">';

                        $res .= '<a href="#"><img src="'.$person['pic'].'" /></a>';

                    $res .= '</div>';

                }
                else {
                    
                    $res .= '<div class="lc-matter-th-person-color lc-matter-th-person-pic-color">';

                        $res .= '<a href="#"><div style="background-color:'.$person['color'].';">'.$person['shortName'].'</div></a>';

                    $res .= '</div>';
                
                }
                
                $res .= '<div class="lc-matter-th-person-pop">';
                    
                    $res .= '<div>'.$person['fullName'].'</div>';
                    
                $res .= '</div>';
                
            
            $res .= '</div>';
            return $res;
        }
        
        
    }
    