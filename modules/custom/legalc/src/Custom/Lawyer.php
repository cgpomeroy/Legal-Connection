<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Lawyer extends Custom\LCUser
    {
        //protected $namespace = __NAMESPACE__;
        
        public static function getUser($uid = null, $user = null)
        {
            if($user != null){
                if(!isset(self::$users[$user->id()])){
                    self::$users[$user->id()] = new Custom\Lawyer($user->id(), $user);
                    return self::$users[$user->id()];
                }
            }
            else {
                $_uid = ($uid == null) ? \Drupal::currentUser()->id() : $uid;
                if(!isset(self::$users[$_uid])){
                    self::$users[$_uid] = new Custom\Lawyer($_uid);
                    return self::$users[$_uid];
                }
                else {
                    return self::$users[$_uid];
                }
            }
        }
        
        
        public function __construct($uid = null, $user = null)
        {
            parent::__construct($uid, $user);
        }
        
        
        public function lawyerProfile()
        {
            ///$banner = new Custom\Banner(true, 'Lawyer', true);
            //$banner->SetPlusButton('/node/add/matter?destination=matters');
            $banner = new Custom\Banner();
            ///$banner->SetSearch(new Custom\Search('lawyers'));
            $b = $banner->renderBanner();
            
            $uid = $this->getUID();
            $chatBtn = '<a href="#" class="matter-detail-chat-btn"><i class="icon-chat-empty"></i><span>Chat</span></a>';
            
            //$q = nl2br( $this->getQualification() );
            //kint($q);
            
            //$exp = nl2br( $this->getExperience() );
            
            $rating = $this->getFiveStarRating(null);
            
            $art = $this->getProfileAverageResponseTime();
                    
            $md = $this->getMembershipDuration();
            
            $exp = $this->yearsOfExperience();
            
            $ctBtn = $this->getContactLawyerButton();
            $apBtn = $this->getAppointmentButton();
            
            $sidebar = $this->renderLawyerProfileSidebar($ctBtn, $apBtn);
            
            //lawyer tab menu
            $tabs = new Custom\Tabs(
                array('Work Details' => array('url' => 'lawyer/profile/word-details/'.$uid, 'icon' => '<i class="icon-connectdevelop"></i>', 'id' => 'work-details'), 
                      'Circulars' => array('url' => 'lawyer/profile/circulars/'.$uid, 'icon' => '<i class="icon-camera-alt"></i>', 'id' => 'circulars'),
                      'Reviews' => array('url' => 'lawyer/profile/reviews/'.$uid, 'icon' => '<i class="icon-commenting-o"></i>', 'id' => 'reviews'),
                      'FAQS' => array('url' => 'lawyer/profile/faqs/'.$uid, 'icon' => '<i class="icon-question-circle-o"></i>', 'id' => 'faqs'),
                      'News Feed' => array('url' => 'lawyer/profile/news-feed/'.$uid, 'icon' => '<i class="icon-newspaper"></i>', 'id' => 'news-feed')
                )
            );
            $tabs->setSidebar($sidebar);
            $tabsRendered = $tabs->render();
            
            
            
            
            $renderable = [
              '#theme' => 'lawyer',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array('uid' => $uid, 'fullname' => $this->getUserFullName(), 'firm' => $this->getFirmName(), 'chatBtn' => $chatBtn,
                               'phone' => $this->getUserPhoneNumber(), 'email' => $this->getUserEmailAddress(), 'userPic' => $this->getUserPicturePath(),
                               'rating' => $rating['html'], 'art' => $art, 'md' => $md, 'exp' => $exp, 'position' => $this->getLawyerPosition(),
                               'ctBtn' => $ctBtn, 'apBtn' => $apBtn, 'tabs' => $tabsRendered,
                               )
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
            
            
            
        }
        
        public function renderLawyerProfileSidebar($ctBtn, $apBtn)
        {
            $othersHired = $this->sidebarOthersHired();
            $adverts = $this->sponsoredAdverts();
            $subscribe = $this->subscribeNotification();
            $press = $this->pressCoverage();
            
            
            $renderable = [
              '#theme' => 'lawyerProfileSidebar',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array(
                  'ctBtn' => $ctBtn, 'apBtn' => $apBtn, 'othersHired' => $othersHired, 'adverts' => $adverts, 'subscribe' => $subscribe, 'press' => $press
              )
            ];
            return drupal_render($renderable);
        }
        
        public function workDetails()
        {
            $about = $this->getAboutLawyer();
            $industry = $this->getLawyerIndustry();
            $expertise = $this->getLawyerExpertise();
            $education = $this->getLawyerEducation();
            $location = $this->getLawyerLocation();
            $obligation = $this->getLawyerCustomerObligation();
            
            $renderable = [
              '#theme' => 'lawyerWorkDetails',
              //'#data' => array('banner' => $b, 'tabs' => $tabsRendered),
              '#data' => array(
                'about' => $about, 'industry' => $industry, 'expertise' => $expertise, 'education' => $education, 'location' => $location, 'obligation' => $obligation,
              )
            ];
            return drupal_render($renderable);
        }
        
        public function getAboutLawyer()
        {
            //field_about_you
            $res = $this->user->get('field_about_you')->getValue();
            return nl2br($res[0]['value']);
        }
        
        public function getLawyerCustomerObligation()
        {
            $res = $this->user->get('field_customer_obligation')->getValue();
            return nl2br($res[0]['value']);
        }
        
        public function getLawyerLocation()
        {
            $street = $this->user->get('field_street_and_number')->getValue()[0]['value'];
            $suburb = $this->user->get('field_suburb')->getValue()[0]['value'];
            $city = $this->user->get('field_city')->getValue()[0]['value'];
            $fullAddress = $street.', '.$suburb.', '.$city;
            
            $hours = '<div><i class="icon-clock-2"></i> '.$this->user->get('field_operating_hours')->getValue()[0]['value'].'</div>';
            //$add = $this->user->get('field_address')->getValue()[0]['value'];
            $address = '<div><i class="icon-location"></i> '.$fullAddress.'</div>';
            $map = '<iframe class="lc-lp-loc-map" style="border:0" src="https://maps.google.com/maps?hl=en&amp;q='.urlencode($fullAddress).'&amp;t=m&amp;z=14&amp;output=embed" width="500" height="200" frameborder="0"></iframe>';
            
            return array('street' => $street, 'suburb' => $suburb, 'city' => $city, 'fullAddress' => $fullAddress, 'hours' => $hours, 'address' => $address, 'map' => $map);
            
        }
        
        public function getLawyerEducation()
        {
            $res = $this->user->get('field_education')->getValue();
            //kint_require();
            //\Kint::$maxLevels = 5;
            //kint($res);
            
            $ret = '';
            foreach($res as $k1 => $v1){
                $ret .= '<div><i class="icon-graduation-cap"></i> '.$v1['value'].'</div>';
            }
            return $ret;
        }
        
        public function getLawyerIndustry()
        {
            $res = $this->user->get('field_industry')->getValue();
            //kint_require();
            //\Kint::$maxLevels = 5;
            //kint($res);
            $terms = array();
            foreach($res as $k1 => $v1){
                //kint($v1);
                $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($v1['target_id']);
                //kint($term);
                $termName = $term->getName();
                //kint($termName);
                $rendered = '<span><i class="icon-ok"></i> '.$termName.'</span>';
                $terms[] = array('termName' => $termName, 'rendered' => $rendered);
            }
            //kint($terms);
            return $terms;
        }
        
        public function getLawyerExpertise()
        {
            $res = $this->user->get('field_expertise')->getValue();
            //kint_require();
            //\Kint::$maxLevels = 5;
            //kint($res);
            $terms = array();
            foreach($res as $k1 => $v1){
                //kint($v1);
                $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($v1['target_id']);
                //kint($term);
                $termName = $term->getName();
                //kint($termName);
                $rendered = '<span><i class="icon-ok"></i> '.$termName.'</span>';
                $terms[] = array('termName' => $termName, 'rendered' => $rendered);
            }
            //kint($terms);
            return $terms;
        }
        
        public function getFirmName()
        {
            return 'ABC & Associates';
        }
        
        public function getQualification()
        {
            $res = $this->user->get('field_experience')->getValue();
            return $res[0]['value'];
        }
        
        public function getExperience()
        {
            $res = $this->user->get('field_experience')->getValue();
            return $res[0]['value'];
        }
        
        public function getLawyerPosition()
        {
            $res = $this->user->get('field_position_in_firm')->getValue();
            return $res[0]['value'];
        }
        
        public function getFiveStarRating($user = null)
        {
            //TODO: Get rating (calculated when user votes)
            //TODO: Generate html
            //TODO: If $user == null use $this->user
            
            $rating = 3.5;
            $numReviews = 39;
            $html = '<div class="lc-fivestar" data-rating="'.$rating.'" data-readonly="true">'.
                    '<span class="lc-rating">rateyo</span><span class="lc-rating-based">Based on '.$numReviews.' reviews</span></div>';
            
            return array('rating' =>  $rating, 'html' => $html);
        }
        
        public function getProfileAverageResponseTime($user = null)
        {
            //TODO: calculate average response time
            return '<span class="lc-lp-art"><i class="icon-clock-2"></i> Avg. Response time: 8 hours</span>';
        }
        
        public function getMembershipDuration($user = null)
        {
            //TODO: calculate membership duration
            return '<span class="lc-lp-md"><i class="icon-user"></i> Member for 2 years</span>';
        }
        
        public function yearsOfExperience($user = null)
        {
            //TODO: get years of experience field value
            return '<span class="lc-lp-md"><i class="icon-ok-circled"></i> 23 Years Experience</span>';
        }
        
        public function getContactLawyerButton($user = null)
        {
            return '<a href="#" class="lc-button" id="">Contact Lawyer</a>';
        }
        
        public function getAppointmentButton($user = null)
        {
            return '<a href="#" class="lc-button-light" id="">Book an Appointment</a>';
        }
        
        
        //Render sidebar block of others that hired the lawyer
        public function sidebarOthersHired()
        {
            $data = $this->getLawyerOthersHired();
            
            $title = '<span>52 others hired Rob</span><a href="#">Reviews ></a>';
            $content = '<div class="lc-la-oh"><a href="#"><i class="icon-user"></i></a>'
                    . '<a href="#"><i class="icon-user"></i></a>'
                    . '<a href="#"><i class="icon-user"></i></a></div>';
            
            
            
            $renderable = [
              '#theme' => 'sidebarBlock',
              '#data' => array('title' => $title, 'content' => $content, 'blockClass' => 'sidebar-others-hired')
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
        }
        //Get other that hired the lawyer from db
        public function getLawyerOthersHired()
        {
            
            return array();
        }
        
        public function sponsoredAdverts()
        {
            $data = $this->getLawyerSponsoredAds();
            
            $title = '<span>Sponsored Adverts</span>';
            $content = '<div class="lc-la-sa"><i class="icon-picture"></i></div>';

            
            
            $renderable = [
              '#theme' => 'sidebarBlock',
              '#data' => array('title' => $title, 'content' => $content, 'blockClass' => 'sidebar-sponsored-ads')
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
        }
        
        public function getLawyerSponsoredAds()
        {
            
            return array();
        }
        
        public function subscribeNotification()
        {
            $data = $this->getSubscribeForm();
            
            $title = '<span>Subscribe for Notification</span>';
            $content = '<div class="lc-la-sa">Subscribe form and text will be here.</div>';
            
            
            $renderable = [
              '#theme' => 'sidebarBlock',
              '#data' => array('title' => $title, 'content' => $content, 'blockClass' => 'sidebar-subscribe')
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
        }
        public function getSubscribeForm()
        {
            
            return array();
        }
        
        public function pressCoverage()
        {
            
            $data = $this->getLawyerPressCoverage();
            
            $title = '<span>Press Coverage of Lawyer</span>';
            $content = '<div class="lc-la-sa">Press coverage links will be here.</div>';
            
            
            
            $renderable = [
              '#theme' => 'sidebarBlock',
              '#data' => array('title' => $title, 'content' => $content, 'blockClass' => 'sidebar-press-coverage')
              /////'#attached' => array('library' => array('legalc/legalc-default'),
              /////                     'drupalSettings' => array('legalc' => array('legalc-default' => array('for' => 'bar', 'baz' => 'test'))))
            ];
            return drupal_render($renderable);
        }
        
        public function getLawyerPressCoverage()
        {
            
            return array();
        }
        public function renderListing($action, $nid)
        {
            $loc = $this->getLawyerLocation();
            
            $ret = '<div class="lc-data-list-item lc-data-user-item" data-user-nid="'.$this->user->id().'">';
            
                $ret .= '<div class="lc-data-list-item-inner">';
                
                    $ret .= '<div class="lc-data-list-item-left">';

                        
                        $ret .= '<div class="lc-data-list-item-text1">';
                            $ret .= '<a href="/lawyer/profile/'.$this->user->id().'">'.$this->getUserFullName().'</a>';
                        $ret .= '</div>';
                        
                        $ret .= '<div class="lc-data-list-text2">';
                            $ret .= $this->getLawyerPosition();
                        $ret .= '</div>';
                        
                        $ret .= '<div class="lc-data-list-item-text3">';
                            $ret .= '<span class="lc-data-list-item-user-firm-icon"><i class="icon-bookmark-empty"></i></span><span class="lc-data-list-item-user-firm-name">'.$this->getFirmName().'</span>';
                        $ret .= '</div>';
                        
                        $ret .= '<div class="lc-data-list-item-text4">';
                            $ret .= '<span class="lc-data-list-item-location-icon"><i class="icon-attach-2"></i></span><span class="lc-data-list-item-location">'.$loc['suburb'].', '.$loc['city'].'</span>';
                        $ret .= '</div>';
                        
                        
                    $ret .= '</div>';
                    
                    $ret .= '<div class="lc-data-list-item-right">';
                    
                        if($action == 'refer'){
                            $ret .= '<a href="/node/add/refer_matter?matter='.$nid.'&lawyer='.$this->user->id().'&destination=matter/'.$nid.'"><i class="icon-plus-circle"></i><span class="">Refer to matter</span></a>';
                        }
                        else {
                            $ret .= '<a href="#"><i class="icon-plus-circle"></i><span class="">Invite to matter</span></a>';
                        }

                    $ret .= '</div>';
                    
                $ret .= '</div>';
            
            $ret .= '</div>';
            
            return $ret;
        }
        
            
            
    }