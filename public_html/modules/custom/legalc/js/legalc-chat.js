(function($, w, Drupal, drupalSettings){
    
    
    var Chat = new Class(w.luna);
    
    //var Chat = new Class({
    Chat.extend({
        initialize: function () {
            this.ably;
            this.channel;
            this.channelName;
            this.mid;
            this.baseUrl = '';
            this.chatWrap = '.lc-chat-messages';
            this.messages = [];
            this.youUid;
            
            this.baseUrl = drupalSettings.legalc.baseUrl;
            this.mid = drupalSettings.legalc.chat.mid;
            this.channelName = drupalSettings.legalc.chat.channel;
            this.youUid = drupalSettings.legalc.uid;

            this.ably = new Ably.Realtime('3M6ioQ.hMpE5g:LHPNy7RG5ukYrx4H');
            this.ably.connection.on('connected', function() {
                console.log("That was simple, you're now connected to Ably in realtime through Chat class.js");
            }); 
            
            this.channel = this.ably.channels.get(this.channelName);
            this.channel.subscribe('chatmsg', this.proxy(this.ablyMessagePosted));

            this.handleMessagePosting();
            //this.fetchOldMessages();
        
         
            
        },
        ablyMessagePosted: function(message){
            //console.log("Received a greeting message in realtime using the Chat classjs: " + message.data);
            //console.log(message);
            //$('.lc-chat-messages').append(message.data);

            var msg = this.storeMessage(message);
            this.displayMessage(msg);
            this.scrollMsgBoxToBottom();
        },
        //Save the message in array
        storeMessage: function(msg){
            //console.log(msg);

            var data = JSON.parse(msg.extras);
            //console.log(data);
            msg.extraData = data;
            var time = data[5];
            //console.log(time);
            this.messages.push(msg);
            //console.log(messages);
            return msg;
        },
        //Handle displaying each message
        displayMessage: function(msg){
            //console.log(msg);
            $(this.chatWrap).append(this.formatMessageDisplay(msg));

            //TODO: CHANGE TO THE WRAPPER DIV LATER
            //$( "body" ).scrollTop( 3000000 );

        },
        //Format message display
        formatMessageDisplay: function(msg){

            if(msg.extraData[1] == this.youUid){
                //display your message
                var msg = '<div class="lc-chat-message lc-chat-you">'+
                                '<div class="lc-chat-msg-data">'+
                                    '<div class="lc-chat-msg-user-data">'+
                                        '<span class="lc-chat-msg-time">'+msg.extraData[4]+'</span>'+
                                        '<span class="lc-chat-msg-name">'+msg.extraData[0]+'</span>'+
                                    '</div>'+
                                    '<div class="lc-chat-msg-message">'+
                                        '<div class="lc-chat-msg-inner">'+
                                            msg.data+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="lc-chat-msg-pic">'+
                                    '<img src="'+msg.extraData[2]+'" />'+
                                '</div>'+
                          '</div>';


            }
            else {
                //display other users message
                var msg = '<div class="lc-chat-message lc-chat-other-user">'+

                                '<div class="lc-chat-msg-pic">'+
                                    '<img src="'+msg.extraData[2]+'" />'+
                                '</div>'+

                                '<div class="lc-chat-msg-data">'+
                                    '<div class="lc-chat-msg-user-data">'+
                                        '<span class="lc-chat-msg-name">'+msg.extraData[0]+'</span>'+
                                        '<span class="lc-chat-msg-time">'+msg.extraData[4]+'</span>'+
                                    '</div>'+
                                    '<div class="lc-chat-msg-message">'+
                                        '<div class="lc-chat-msg-inner">'+
                                            msg.data+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+

                          '</div>';
            }

            return msg;

        },
        scrollMsgBoxToBottom: function(){
            //////////var sHeight = $('.lc-chat-messages')[0].scrollHeight;
            //Scrolling the element to the sHeight
            //////////$('.lc-chat-messages').scrollTop(sHeight);
            
            //var sHeight = $('body')[0].scrollHeight;
            //Scrolling the element to the sHeight
            $('html, body').scrollTop($(document).height());
        },
        //Post message from chat screen to legalconnection
        handleMessagePosting: function(){
            $('.region.region-content').on('click', '#lc-chat-submit', 
                this.proxy(this.handleMessagePostingClicked));
        },
        handleMessagePostingClicked: function(e){
            //$('.region.region-content').on('click', '#lc-chat-submit', function(){
                //console.log('sending message');

                e.preventDefault();
                
                var msg = $('#lc-chat-input').val();
                //console.log(msg);
                if(msg == ""){
                    return;
                }

                this.showActionGif();

                $.ajax({
                    method: "POST",
                    url: this.baseUrl+"/chat/message",
                    data: {_mid: this.mid, _msg: msg},
                    dataType:"json"
                })
                .done( this.proxy(this.messagePostedDone))
                .fail(function() {
                    //console.log( "error sending chat" );
                    alert("There was an error, please try again");
                    this.hideActionGif();
                })
                .always(this.proxy(this.messagePostedAlways));


            //});
        },
        messagePostedDone: function(msg){
            //this.hideActionGif();
            $('#lc-chat-input').val("");
        },
        messagePostedAlways: function(){
            this.hideActionGif();
        },
        //Show/hide animated preloader on sending message
        showActionGif: function(){
            $('#lc-chat-submit').hide();
            $('.btn-action-gif').show();
        },
        hideActionGif: function(){
            $('.btn-action-gif').hide();
            $('#lc-chat-submit').show();
        },
        //Fetch previous messages on page load
        fetchOldMessages: function(tab, msg){
            
            if(tab == 'chat'){
                if(this.messages.length < 1){

                    $.ajax({
                        method: "POST",
                        url: this.baseUrl+"/chat/archive?matter="+this.mid,
                        data: {},
                        dataType:"json"
                    })
                    .done(this.proxy(this.oldMessagesFetched))
                    /////.done( function(msgs){
                        //console.log('older messages:');
                        /////console.log(msgs);
                        /////storeAndDisplayOldMessages(msgs);
                        /////scrollMsgBoxToBottom();
                        ////////////this.storeOldMessages(msgs);
                        /////this.proxy(this.storeOldMessages);
                        //console.log(messages);
                    /////})
                    .fail(function() {
                        //console.log( "error" );

                    })
                    .always(function() {

                    });

                }
                else {
                    this.displayOldMessages();
                }
            }    
        },
        oldMessagesFetched: function(msgs){
            console.log('oldMessagesFetched');
            this.storeOldMessages(msgs);
            //console.log(this.messages);
            this.displayOldMessages();
        },
        storeOldMessages: function(msgs){
            for(var i in msgs.data){
                this.storeMessage(msgs.data[i]);
            }
        },
        displayOldMessages: function(){
            for(var i in this.messages){
                this.displayMessage(this.messages[i]);
            }
        },
        fitChatToScreen: function(){
            
        }

    });
    
    w.myChat = Chat;
    
    $(document).ready(function(){
       
        //var myChat = new Chat();
        //console.log(myChat);
        //w.events.push({name: 'chatStart', obj:myChat, method:'displayOldMessages'});
    });
    
    
    
    
    
    
    /*
    
    var ably;
    var channel;
    var channelName = ''; //DrupalSetting
    var mid;
    var baseUrl = '';
    var chatWrap = '.lc-chat-messages';
    var messages = [];
    var youUid;
    
    w.events.testFunc = function(t){
        console.log('testFUNC');
    };
    
    $(document).ready(function(){
        
        
        var myChat = new Chat();
        console.log('myChat');
        console.log(myChat);
        
        
        console.log(this);
        
        console.log('legalc-chat.js');
        console.log(drupalSettings);
        baseUrl = drupalSettings.legalc.baseUrl;
        mid = drupalSettings.legalc.chat.mid;
        channelName = drupalSettings.legalc.chat.channel;
        //console.log(channelName);
        youUid = drupalSettings.legalc.uid;
        
        
        
        var ably = new Ably.Realtime('3M6ioQ.hMpE5g:LHPNy7RG5ukYrx4H');
        ably.connection.on('connected', function() {
            console.log("That was simple, you're now connected to Ably in realtime");
        });  

        var channel = ably.channels.get(channelName);
        channel.subscribe('chatmsg', function(message) {
            console.log("Received a greeting message in realtime: " + message.data);
            console.log(message);
            //$('.lc-chat-messages').append(message.data);
            
            
            
            var msg = storeMessage(message);
            displayMessage(msg);
            scrollMsgBoxToBottom();
        });
        
        handleMessagePosting();
        fetchOldMessages();
        
        
    });
    
    
    //Fetch previous messages on page load
    fetchOldMessages = function(){
            $.ajax({
                method: "POST",
                url: baseUrl+"/chat/archive?matter="+mid,
                data: {},
                dataType:"json"
            })
            .done( function(msgs){
                //console.log('older messages:');
                //console.log(msgs);
                /////storeAndDisplayOldMessages(msgs);
                /////scrollMsgBoxToBottom();
                storeOldMessages(msgs);
                //console.log(messages);
            })
            .fail(function() {
                //console.log( "error" );
                
            })
            .always(function() {
                
            });
    };
    
    storeOldMessages = function(msgs){
        for(var i in msgs.data){
            storeMessage(msgs.data[i]);
        }
    };
    displayOldMessages = function(){
        for(var i in messages){
            displayMessage(messages[i]);
        }
    };
    
    
    //OLD - REMOVE
    storeAndDisplayOldMessages = function(msgs){
        for(var i in msgs.data){
            var msg = storeMessage(msgs.data[i]);
            displayMessage(msg);
        }
    };
    
    //Post message from chat screen to legalconnection
    handleMessagePosting = function(){
        $('.region.region-content').on('click', '#lc-chat-submit', function(){
            console.log('sending message');
            
            var msg = $('#lc-chat-input').val();
            //console.log(msg);
            if(msg == ""){
                return;
            }
            
            showActionGif();
            
            $.ajax({
                method: "POST",
                url: baseUrl+"/chat/message",
                data: {_mid: mid, _msg: msg},
                dataType:"json"
            })
            .done( function(msg){
                //console.log(msg);
                hideActionGif();
                $('#lc-chat-input').val("");
            })
            .fail(function() {
                //console.log( "error sending chat" );
                alert("There was an error, please try again");
                hideActionGif();
            })
            .always(function() {
                //console.log( "send chat complete" );
                hideActionGif();
            });
                      
                      
        });
    };
    
    //Save the message in array
    storeMessage = function(msg){
        //console.log(msg);
        
        var data = JSON.parse(msg.extras);
        //console.log(data);
        msg.extraData = data;
        var time = data[5];
        //console.log(time);
        messages.push(msg);
        //console.log(messages);
        return msg;
    };
    
    //Handle displaying each message
    displayMessage = function(msg){
        
        $(chatWrap).append(formatMessageDisplay(msg));
        
        //TODO: CHANGE TO THE WRAPPER DIV LATER
        //$( "body" ).scrollTop( 3000000 );
        
    };
    
    //Format message display
    formatMessageDisplay = function(msg){
        
        if(msg.extraData[1] == youUid){
            //display your message
            var msg = '<div class="lc-chat-message lc-chat-you">'+
                            '<div class="lc-chat-msg-data">'+
                                '<div class="lc-chat-msg-user-data">'+
                                    '<span class="lc-chat-msg-time">'+msg.extraData[4]+'</span>'+
                                    '<span class="lc-chat-msg-name">'+msg.extraData[0]+'</span>'+
                                '</div>'+
                                '<div class="lc-chat-msg-message">'+
                                    '<div class="lc-chat-msg-inner">'+
                                        msg.data+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="lc-chat-msg-pic">'+
                                '<img src="'+msg.extraData[2]+'" />'+
                            '</div>'+
                      '</div>';


        }
        else {
            //display other users message
            var msg = '<div class="lc-chat-message lc-chat-other-user">'+
                    
                            '<div class="lc-chat-msg-pic">'+
                                '<img src="'+msg.extraData[2]+'" />'+
                            '</div>'+
                            
                            '<div class="lc-chat-msg-data">'+
                                '<div class="lc-chat-msg-user-data">'+
                                    '<span class="lc-chat-msg-name">'+msg.extraData[0]+'</span>'+
                                    '<span class="lc-chat-msg-time">'+msg.extraData[4]+'</span>'+
                                '</div>'+
                                '<div class="lc-chat-msg-message">'+
                                    '<div class="lc-chat-msg-inner">'+
                                        msg.data+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            
                      '</div>';
        }
        
        return msg;
        
    };
    
    //Show/hide animated preloader on sending message
    showActionGif = function(){
        $('#lc-chat-submit').hide();
        $('.btn-action-gif').show();
    };
    hideActionGif = function(){
        $('.btn-action-gif').hide();
        $('#lc-chat-submit').show();
    };
    
    scrollMsgBoxToBottom = function(){
        //////////var sHeight = $('.lc-chat-messages')[0].scrollHeight;
        //Scrolling the element to the sHeight
        //////////$('.lc-chat-messages').scrollTop(sHeight);
    };
    
    */
    
    
    
})(jQuery, window, Drupal, drupalSettings);


    