<?php

    namespace Drupal\legalc\Custom;
    
    use Drupal\legalc\Custom;
    
    class Chat
    {
        
        private $mid;
        private $matter;
        private $msgCreatedTime;
        
        public function __construct($mid)
        {
            $this->mid = $mid;
        }
        
        //Get the channel name used by ably.io
        //Channel name is unique, but always the same per matter
        public function getChannelName()
        {
            //query db for channel name for this matter
            //if empty create channel name
              //save channel name in db
            //return channel name
            
            $cn = $this->gueryMatterChannelName();
            if(!$cn){
                $cn = $this->generateChannelName();
                $this->saveChannelName($cn);
            }
            
            return $cn;
        }
        
        private function saveChannelName($cn)
        {
            $connection = \Drupal::database();
            $result = $connection->insert('lc_matter_channel')
                ->fields([
                  'mid' => $this->mid,
                  'lc_channel' => $cn,
                  'lc_data' => '',
                  'created' => time(),
                ])
                ->execute(); 
        }
        
        private function gueryMatterChannelName()
        {
            $connection = \Drupal::database();
            $q = $connection->query("SELECT mc.lc_channel FROM {lc_matter_channel} mc ".
                    "WHERE mc.mid = :mid", 
                    array(':mid' => $this->mid));
            $record = $q->fetchField();
            //kint($record);
            //if($record == false){
            //    kint('record is false');
            //}
            return $record;
        }
        
        private function generateChannelName()
        {
            $hash = md5( $this->mid.time().rand(1, 10000).'ZnNWf57\i7r%+^4&0DdMJTHl]!D)#Fq4~!>ACHe~' );
            $channel = substr($hash, 0, 13).$this->mid;
            return $channel;
        }
        
        public function renderStart()
        {
            //send matter id and channel name to js
            
            //$cn = $this->getChannelName();
            
            $renderable = [
              '#theme' => 'chatScreen',
              '#data' => array(),
            ];
            return drupal_render($renderable);
        }
        
        
        //Check access permissions
        //Generate or get (same function) channel name
        //Save msg
        //Publish to ably.io
        public function newMessage($msg)
        {
              
            //save message in db
            
            $this->msgCreatedTime = time();
            $this->saveNewMessage($msg);
            $this->publishMessage($msg);

            

            //return $this->getMessageUser();
            
            
            //return 'This is a return of the posted message';
            
        }
        
        //Save chat message in db
        public function saveNewMessage($msg)
        {
            
            $connection = \Drupal::database();
            $result = $connection->insert('lc_chat_messages')
                ->fields([
                  'mid' => $this->mid,
                  'uid' => LCUser::getCurrentUserUid(),
                  'chat_type' => 'message',
                  'lc_message' => $msg,
                  'lc_message_file' => 0,
                  'lc_data' => '',
                  'created' => $this->msgCreatedTime,
                  
                ])
                ->execute(); 
        }
        
        //Send msg to ably to be published
        public function publishMessage($msg)
        {
            $ch = curl_init();
            
            $url = "https://rest.ably.io/channels/".$this->getChannelName()."/publish";
            
            //$message = 'path1=123&arg2=345&arg5=i677456';
            //$message = json_encode($message);
            
            $msgBr = nl2br($msg);
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, "name=chatmsg&data=".$msg."&extras=<b>123</b>:<img src=\"#\" class=\"test\" />456:7890:<em>434326545</em>");
            curl_setopt($ch, CURLOPT_POSTFIELDS, "name=chatmsg&data=".$msgBr.$this->generateMessageExtras());
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_USERPWD, "3M6ioQ.hMpE5g" . ":" . "LHPNy7RG5ukYrx4H");

            $headers = array();
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);
        }
        
        private function generateMessageExtras()
        {
            $msgUser = $this->getMessageUser();
            
            $fullName = $msgUser->getUserFullName();
            $uid = $msgUser->getUID();
            $pp = $msgUser->getUserPicturePath();
            $color = Custom\Colors::getUserColor($uid);
            $time = $this->msgCreatedTime;
                    
            $res = array($fullName, $uid, $pp, $color, $time);
            $res = json_encode($res);
            return '&extras='.$res;
        }
        public function getMessageUser()
        {
            return $lcUser = Custom\LCUser::getUser();
            //$roles = $lcUser->getUserRoles();
            
            //return array('lcUser' => $lcUser, 'roles' => $roles);
        }
        
        //Get the stored chat older messages
        public function getChatOlderMessages()
        {
            
            $connection = \Drupal::database();
            $q = $connection->query("SELECT * FROM {lc_chat_messages} cm ".
                    "WHERE cm.mid = :mid ORDER BY cm.created ASC", 
                    array(':mid' => $this->mid));
            $records = $q->fetchAll();
            
            $msgUsers = array();
            
            $messages = array();
            foreach($records as  $row){
                //kint($row);
                if(!isset($msgUsers[$row->uid])){
                    $msgUsers[$row->uid] = Custom\LCUser::getUser($row->uid);
                }
                
                $data = new \stdClass();
                $data->data = nl2br($row->lc_message);
                
                $fullName = $msgUsers[$row->uid]->getUserFullName();
                $pp = $msgUsers[$row->uid]->getUserPicturePath();
                $color = Custom\Colors::getUserColor($row->uid);
                $time = $row->created;
                $extras = array($fullName, $row->uid, $pp, $color, $time);
                $data->extras = json_encode($extras);
                
                $messages[] = $data;
            }
            
            //kint($msgUsers);
            //kint($messages);
            
            return $messages;
            
        }
        
        //Instantiate matter
        public function loadChatMatter()
        {
            
        }
        
        public function getContextMenuValue()
        {
            $link1 = new \stdClass();
            $link1->iconClass = 'icon-plus-circle';
            $link1->linkTitle = 'File Upload';
            $link1->url = '/node/add/documents?matter=69&destination=matter/69';
            
            $link2 = new \stdClass();
            $link2->iconClass = 'icon-plus-circle';
            $link2->linkTitle = 'Create Matter';
            $link2->url = '/node/add/matter?destination=matters';
            
            $link3 = new \stdClass();
            $link3->iconClass = 'icon-plus-circle';
            $link3->linkTitle = 'Create Matter';
            $link3->url = '/node/add/matter?destination=matters';
            
            
            return array($link1, $link2, $link3);
            
        }
        
    }
    
    