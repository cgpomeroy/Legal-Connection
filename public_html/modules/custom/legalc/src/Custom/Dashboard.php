<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Dashboard
    {
        protected $user;
        protected $uid;
        protected $state;
        protected $matterNID;
        
        public function __construct($mid = null)
        {
            $this->user = Custom\LCUser::getUser();
            //kint($user->user);
            $this->uid = $this->user->user->id();
            if($mid != null){
                $this->matterNID = $mid;
            }
            
        }
        
        public function buildDashboard()
        {
            $state = Custom\MyMobileDetect::detectState();
            $this->state = $state;
            if($state == 'mobile'){
                return $this->buildDashboardMobile();
            }
            else {
                return $this->buildDashboardDesktop();
            }
        }
        
        
        private function buildDashboardDesktop()
        {
            $banner = new Custom\Banner();
            $b = $banner->renderBanner();
            
            /////$mid = (int)$_GET['matter'];
            
            $matters = new Custom\Matters();
            $mattersPage = $matters->getAllMattersUser($this->matterNID);
            
            /* if(is_null($this->matterNID)){
                $matterTabs = "";
            } else {
                $mid = $this->matterNID;
                $matter = new Custom\Matter(null, $mid);
                $matterTabs = $matter->renderDesktopMatterPage();
            } */
            
            
            $renderable = [
                '#theme' => 'dashboardDesktop',
                '#data' => array('matterTabs' => '', 'mattersSidebar' => $mattersPage),
            ];
            return drupal_render($renderable);
            
        }
        
        
        private function buildDashboardMobile()
        {
            
          $uid = $this->uid;

          $numberMatters = Custom\Matters::GetLawyerMatterCount($uid);

          $numberClients = Custom\Clients::GetLawyerClientCount($uid);

          $numberQuotes = Custom\Quotes::GetLawyerQuoteCount($uid);

          $numberLeads = Custom\WebLeads::GetLawyerWebLeadsCount($uid);    

          $upcomingEvents = Custom\Events::getLawyerUpcomingEvents($uid);

          $banner = new Custom\Banner('Dashboard');
          $b = $banner->renderBanner();


          $renderable = [
            '#theme' => 'dashboard',
            '#data' => array('matterCount' => $numberMatters, 'clientCount' => $numberClients, 'quoteCount' => $numberQuotes, 'webLeadsCount' => $numberLeads,
                             'dashMenu' => $this->dashboardMobileMenu(), 'upcomingEvents' => $upcomingEvents),
          ];
          return drupal_render($renderable);

        }
  
        private function dashboardMobileMenu()
        {
            return '<div id="dashboard-menu">'.
                      '<a href="/node/add/matter?destination=matters" class="dash-menu-item1">'.
                          '<img src="/themes/legalconnect/images/dashboard/quick-quote.png" />'.
                          '<span>New Matter</span>'.
                      '</a>'.
                      '<a href="#" class="dash-menu-item2">'.
                          '<img src="/themes/legalconnect/images/dashboard/chats.png" />'.
                          '<span>Chats</span>'.
                      '</a>'.
                      '<a href="#" class="dash-menu-item3">'.
                          '<img src="/themes/legalconnect/images/dashboard/log-hours.png" />'.
                          '<span>Log Hours</span>'.
                      '</a>'.
                      '<a href="#" class="dash-menu-item4">'.
                          '<img src="/themes/legalconnect/images/dashboard/recent-matters.png" />'.
                          '<span>Recent Matters</span>'.
                      '</a>'.
                    '</div>';
        }
  
  
        
        
        
    }