

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};

var LunaEvents = new Class({
    _callbacks: {},
    proxy: function (func) {
        var thisObject = this;
        return(function(){ 
            return func.apply(thisObject, arguments); 
        });
    },
    bind: function(ev, callback, scope){
        var evs   = ev.split(" ");
        var calls = this._callbacks || (this._callbacks = {});

        for (var i=0; i < evs.length; i++){
            (this._callbacks[evs[i]] || (this._callbacks[evs[i]] = [])).push( {cb: callback, sc: ((!scope) ? 0 : scope)} );
        }
        return this;
    },
    trigger: function() {
        //console.log(arguments);
        var args = this.makeArray(arguments);
        //console.log(args);
        var ev   = args.shift();
        //console.log(ev);
        //console.log( this._callbacks );

        var list, calls, i, l;
        if (!(calls = this._callbacks)) return this;
        if (!(list  = this._callbacks[ev])) return this;
                //console.log(list);
        for (i = 0, l = list.length; i < l; i++){
            //console.log( list[i] );
            if (list[i].cb.apply(this, args) === false){
            //if (list[i].cb.apply( ((list[i].scope) ? list[i].scope : this) , args) === false){
                return false;
            }
        }
        return this;
    },
    makeArray: function(args){
        return Array.prototype.slice.call(args, 0);
    },
    unbind: function(ev, callback){
        if ( !ev ) {
            this._callbacks = {};
            return this;
        }

        var list, calls, i, l;
        if (!(calls = this._callbacks)) return this;
        if (!(list  = this._callbacks[ev])) return this;

        for (i = 0, l = list.length; i < l; i++) {
            if (callback === list[i]) {
                list.splice(i, 1);
                break;
            }
        }
        return this;
    },
    objLength: function(obj){
        var cnt = 0;
        for(var i in obj){
            cnt++;
        }
        return cnt;
    }
    
});
window.luna = LunaEvents;

//window.events = [];
//window.eventManager = new LunaEvents();

/*
var myTabEvents = new Class(LunaEvents);
myTabEvents.extend({
    addEvent: function (name, obj, method) {
        this.name = name;
        this.obj = obj;
        this.method = method;
    },
    callEvent: function(){
        
    }
});
*/

(function($, w, Drupal, drupalSettings){
    
    
    w.view = new Class({
        stickyMenu: function(){
            var mn = $(".lc-tabs-tabs");
            var mns = "stick-to-top";
            var menu1 = $("#lc-banner-wrap").height();
            var header1 = $('.lc-matter-title-header-wrap').height();
            var total = menu1 + header1;
            
            if( $(window).scrollTop() > total ) {
                mn.addClass(mns);
            } else {
                mn.removeClass(mns);
            }
        },
        isThemeMobile: function(){
            return ($('body').hasClass('lc-site-mobile'));
        }
    });
    
    w.lcTabs = new Class(w.luna);
    w.lcTabs.extend({
        initialize: function () {
            //console.log('lctabs init');
            this.activeTabLink = '';
            this.tabLinkClicked = '';
            //console.log('lctabs init2');
            
            $('.lc-tabs-tabs').on('click', 'a', this.proxy(this.tabClicked));
            this.stateChangeTab();
        },
        //myInit: function(){
        //    $('.lc-tabs-tabs').on('click', 'a', this.proxy(this.tabClicked));
        //},
        tabClicked: function(e){
            //console.log('testetes');
            e.preventDefault();
            var tid = $(e.target).attr('id');
            //console.log(tid);
            this.processTabLink(tid);
            history.pushState(null, null, '?tab='+tid);
        },
        processTabLink: function(tid){
            if(this.activeTabLink != tid){
            
                this.tabLinkClicked = tid;
                
                var ob = $('#'+tid);
            
                var url = ob.attr('href');
                //console.log(url);
                /////activeLink = $(this);
                $('.lc-tabs-content-loader').show();
                $('.lc-tabs-content-data').empty();

                //console.log('testing ajax matter invoices');
                
                $.ajax({
                    method: "POST",
                    url: url
                    //data: { name: "John", location: "Boston" }
                  })
                    .done(this.proxy(this.processTabLinkDone));
            }
        },
        processTabLinkDone: function(msg){
            //console.log(w.events.testFunc('test'));
                      //console.log(arguments);
                      //console.log('processTabLinkDone');
                      console.log(msg);
                      
                      var ob = $('#'+this.tabLinkClicked);
                      $('.lc-tabs-link').removeClass('active');
                      ob.addClass('active');
                      $('.lc-tabs-content-loader').hide();
                      $('.lc-tabs-content-data').html(msg.data.html);
                      activeTabLink = this.tabLinkClicked;
                      
                      //console.log('checking trigger...');
                      //console.log(activeTabLink);
                      
                      //w.eventManager.trigger(activeTabLink, activeTabLink);
                      w.eventManager.trigger('tabLoaded', activeTabLink, msg);
                      
                      //console.log(msg.data.contextMenu);
                      
                      //if(msg.data.contextMenu){
                      //    w.contextMenuObj.setContextMenu(msg.data.contextMenu);
                      //}
                      //else {
                          
                      //}
                      
                      //history.pushState(null, null, '?tab='+tid);
        },
        stateChangeTab: function(){
            //console.log('statechangetab');

            var tid = getUrlParameter('tab');

            if(tid){ 
                //console.log(tid); 
                //$('#'+tb).click();
                this.processTabLink(tid);
            }
            else { 
                //console.log('nottb'); 
                var tid = $('.lc-tabs-link').eq(0).attr('id');
                //console.log(tid);
                if(tid){
                    this.processTabLink(tid);
                }
            }
        }
    });
    
    
    w.contextMenu = new Class(w.luna);
    w.contextMenu.extend({
        contextMenuLinks: [],
        tab: '',
        cWrap: 'context-action-menu',
        initialize: function () {
            this.initContextMenuEvents();
            //////////this.setContextMenu();
            
            console.log( drupalSettings );
            var contextMenu = drupalSettings.legalc.contextMenu;
            if(contextMenu){
                this.setContextMenu(contextMenu);
            }
            else {
                this.setContextMenu();
            }
            
        },
        //Set the contextual menu links and start the render
        setContextMenu: function(links = null){
            this.contextMenuLinks = links;
            //Dummy data
            //this.contextMenuLinks = [
            //    {iconClass: 'icon-plus-circle', linkTitle: 'File Upload', url: '/node/add/documents?matter=69&destination=matter/69'},
            //    {iconClass: 'icon-plus-circle', linkTitle: 'Create Matter', url: '/node/add/matter?destination=matters'}
            //];

            this.removeContextMenu();

            if(this.contextMenuLinks != null){

                this.initContextMenu();
            }
            //else {
                //console.log('hide large icon');
                /////this.hideLargeContextMenuIcon();
            //}
            
            this.handleButtonDisplay();
            
        },
        removeContextMenu: function(){
            $('#'+this.cWrap).remove();
        },
        //Reset and Render the bottom contextual menu
        initContextMenu: function(){
            //console.log(contextMenuLinks);
            /////var cWrap = 'context-action-menu';
            //this.removeContextMenu();

            var html = '<div id="'+this.cWrap+'"><div id="global-action-btns"><a href="#" class="context-menu-open"><i class="icon-plus-circle"></i></a><a href="#" class="context-menu-close"><i class="icon-cancel-circle"></i></a></div>';

            html += '<div class="context-menu-menu-wrap">';
            for(var i in this.contextMenuLinks){
                html += '<div class="context-menu-link-wrap"><a href="'+this.contextMenuLinks[i].url+'"><i class="'+this.contextMenuLinks[i].iconClass+'"></i><span>'+this.contextMenuLinks[i].linkTitle+'</span></a></div>';
            }

            html += '</div></div>';

            $('.main-container').append(html);

            var mh = $('.context-menu-menu-wrap').height();
            //console.log(mh);
            $('.context-menu-menu-wrap').css('top', '-'+mh+'px');

            this.reseatContextMenu();

        },
        reseatContextMenu: function(){
            
            var isMobile = w.viewManager.isThemeMobile();
            console.log('is mobile');
            console.log(isMobile);
            
            var ww = $(window).width();
            
            if(isMobile){
                if(this.tab == 'chat'){
                    $('#context-action-menu').addClass('context-mobile-chat');
                }
                else {
                    $('#context-action-menu').addClass('context-mobile-not-chat');
                }
            }
            else {
                if(this.tab == 'chat'){
                    //var chatBtns = $('.lc-chat-msg-btn');
                    //var pos = chatBtns.position();
                    //console.log(pos);
                    //var cbWidth = chatBtns.outerWidth();
                    //console.log(cbWidth);
                    
                    $('#context-action-menu').addClass('context-desktop-chat');


                    /*
                    var elm = $('.lc-chat-msg-btn');  //get the div
                    var posY_top = elm.offset().top;  //get the position from top
                    console.log(posY_top);
                    var posX_left = elm.offset().left;
                    console.log(posX_left);
                    var width = elm.width();

                    var distR = (ww - posX_left - width + 44);
                    $('#'+this.cWrap).css('right', distR+'px');
                    $('#'+this.cWrap).css('bottom', '42px');
                    */

                    $('#context-action-menu').css('right', 'auto').css('left', dist);
                    var w1 = $('.lc-dashboard-desktop-left').width();
                    var w2 = $('.lc-tabs-content-region').width();
                    var dist = (w1+w2)+'px';
                    $('#context-action-menu').css('right', 'auto').css('left', dist);
                    
                    
                }
                else {
                    $('#context-action-menu').addClass('context-desktop-not-chat');
                    var w1 = $('.lc-dashboard-desktop-left').width();
                    var w2 = $('.lc-tabs-content-region').width();
                    var dist = (w1+w2)+'px';
                    $('#context-action-menu').css('right', 'auto').css('left', dist);
                
                    /*
                    var pw = $('.main-container').width();
                    if(ww > pw){
                        var di = (ww - pw)/2;
                        $('#'+this.cWrap).css('right', di+'px');
                    }
                    else {
                        $('#'+this.cWrap).css('right', 0);
                    }
                    */
                   
                   
                   
                   
                }
            }
            
        },
        //Initiate the contextual menu links
        initContextMenuEvents: function(){
            $('.main-container').on('click', 'a.context-menu-open', function(e){
                e.preventDefault();
                $(this).hide();
                $('a.context-menu-close').show();
                $('div.context-menu-menu-wrap').show();
            });
            $('.main-container').on('click', 'a.context-menu-close', function(e){
                e.preventDefault();
                $(this).hide();
                $('a.context-menu-open').show();
                $('div.context-menu-menu-wrap').hide();
            });
            
            //Handle chat screen context menu
            $('.main-container').on('click', 'a.lc-chat-context-menu-open', function(e){
                e.preventDefault();
                $(this).hide();
                $('a.lc-chat-context-menu-close').show();
                $('div.context-menu-menu-wrap').show();
            });
            $('.main-container').on('click', 'a.lc-chat-context-menu-close', function(e){
                e.preventDefault();
                $(this).hide();
                $('a.lc-chat-context-menu-open').show();
                $('div.context-menu-menu-wrap').hide();
            });
        },
        whichContextMenu: function(tab){
            //console.log('which context menu');
            //console.log(tab);
            /////this.activator = tab;
            switch(tab){
                case 'chat':
                    //console.log('chat page loaded');
                    this.hideLargeContextMenuIcon();
                    break;
                default:
                    this.showLargeContextMenuIcon();
                    break;
            }
        },
        hideLargeContextMenuIcon: function(){
            $('#global-action-btns').hide();
        },
        showLargeContextMenuIcon: function(){
            $('#global-action-btns').show();
        },
        tabChanged: function(tab, msg){
            this.tab = tab;
            if(msg.data.contextMenu != undefined){
                this.setContextMenu(msg.data.contextMenu);
            }
            else {
                this.setContextMenu(null);
            }
            //this.whichContextMenu(tab);
        },
        handleButtonDisplay: function(){
            if(this.tab == 'chat'){
                this.hideLargeContextMenuIcon();
            }
            else {
                if(this.contextMenuLinks == null || this.contextMenuLinks.length < 1){
                    this.hideLargeContextMenuIcon();
                }
                else {
                    this.showLargeContextMenuIcon();
                }
            }
        }
    
    
    });
    
    
    $(document).ready(function(){
        //console.log('doc ready');
        
        w.eventManager = new w.luna();
        
        if(w.myChat){
            w.myChat = new w.myChat();
            //console.log(w.myChat);
            //w.eventManager.bind( 'chat', w.myChat.proxy(w.myChat.fetchOldMessages) );
            w.eventManager.bind( 'tabLoaded', w.myChat.proxy(w.myChat.fetchOldMessages) );
        }
        
        
        w.viewManager = new w.view();
        
        w.contextMenuObj = new w.contextMenu();
        //w.eventManager.bind('chat', w.contextMenuObj.proxy(w.contextMenuObj.whichContextMenu));
        w.eventManager.bind('tabLoaded', w.contextMenuObj.proxy(w.contextMenuObj.tabChanged));
        
        w.tabsManager = new w.lcTabs();
        
        $(window).resize(function(){
            windowResize();
        });
        $(window).scroll(function(){
            w.viewManager.stickyMenu();
        });
        
        ///////stateChangeTab();
        window.addEventListener('popstate', function(event) {
            //console.log('popstate fired!');
            //var tid = getUrlParameter('tab');
            
            var someOtherHistoryLink = null;
            
            if(someOtherHistoryLink == null){
                w.tabsManager.stateChangeTab();
            }
            
        });
        
        
        //Create bottom plus button on every page
        //////////initContextMenuEvents();
        //////////setContextMenu();
        function windowResize()
        {
            //console.log('resize');
            //Place the context menu in the correct place when the window is resized
            //////////reseatContextMenu();
            w.contextMenuObj.reseatContextMenu();
        }
        
        
        //Hide tabs on user pages
        if(!$('body').hasClass('user-logged-in')){
            $('body.path-user ul.tabs--primary li').eq(1).hide();
            $('body.path-user ul.tabs--primary li').eq(4).hide();
        }
        else {
            
            
        }
        
        //Handle menu toggle
        $('#lc-banner-menu').on('click', 'a', function(e){
            e.preventDefault();
            $('#lc-menu').toggle();
        });
        
        
        
        
        //Handle LegalC tabs
        /////activeTabLink = '';
        /*
        $('.lc-tabs-tabs').on('click', 'a', function(e){
            e.preventDefault();
            
            var tid = $(this).attr('id');
            //var qs = $(this).attr('data-tab-query');
            
            processTabLink(tid);
            
            history.pushState(null, null, '?tab='+tid);
            
        });
        */
        
        


        
        
        
        //Buttons that require an alert confirmation
        //https://github.com/claviska/jquery-alertable
        $('.lc-btn-confirm a').on('click', function(e){
            var path = $(this).attr('href');
            var msg = $(this).parent().attr('data-lc-conf');
            
            e.preventDefault();
            
            $.alertable.confirm(msg).then(function() {
                // OK was selected
                window.location.href = path;
            }, function() {
                // Cancelled
            }).always(function() {
                // Modal was dismissed
            });
            
        });
        
        
        $('.lc-fivestar').each(function(i){
            var rating = $(this).attr('data-rating');
            //console.log(rating);
            var readOnly = $(this).attr('data-readonly');
            //console.log(readOnly);
            $(this).find('.lc-rating').rateYo({
                rating:rating,
                halfStar: true,
                readOnly: readOnly,
                starWidth: "20px"
            });
        });
        
        //Create 5 star rateyo
        /*
        $(".lc-rating").rateYo({
            //var rating = $(this).parent().attr('data-rating');
            //var readOnly = $(this).parent().attr('data-readonly');
            rating: $(this).parent().attr('data-rating'),
            halfStar: true,
            readOnly: $(this).parent().attr('data-readonly')
        });
        */
        
        
        //Matter list click
        //TODO: remvove this:
        /*
        $('.region-content').on('click', '.lc-list-matters-item-left', function(e){
            e.preventDefault();
            var nid = $(this).parent().parent().attr('data-matter-nid');
            window.location.href = '/matter/'+nid;
        });
        */

        //Quote list click
        /*
        $('.region-content').on('click', '.lc-list-quotes-item-left', function(e){
            e.preventDefault();
            var nid = $(this).parent().parent().attr('data-quote-nid');
            window.location.href = '/quote/'+nid;
        });
        */
        
        /*
        $.ajax({
            method: "POST",
            url: "some.php",
            data: { name: "John", location: "Boston" }
          })
            .done(function( msg ) {
              alert( "Data Saved: " + msg );
        });
  
  
        var jqxhr = $.ajax( "example.php" )
            .done(function() {
              alert( "success" );
            })
            .fail(function() {
              alert( "error" );
            })
            .always(function() {
              alert( "complete" );
        });
        */
    
    
    
    
            
          
        
    
    
    
    });
    
    
    
    
    /*
    activeTabLink = '';
    function processTabLink(tid)
    {
        if(activeTabLink != tid){
            
                var ob = $('#'+tid);
            
                var url = ob.attr('href');
                //console.log(url);
                /////activeLink = $(this);
                $('.lc-tabs-content-loader').show();
                $('.lc-tabs-content-data').empty();

                //console.log('testing ajax matter invoices');
                
                $.ajax({
                    method: "POST",
                    url: url
                    //data: { name: "John", location: "Boston" }
                  })
                    .done(function( msg ) {
                        
                        //console.log(w.events.testFunc('test'));
                        
                      console.log(msg);
                      $('.lc-tabs-link').removeClass('active');
                      ob.addClass('active');
                      $('.lc-tabs-content-loader').hide();
                      $('.lc-tabs-content-data').html(msg.data.html);
                      activeTabLink = tid;
                      
                      w.eventManager.trigger(tid);
                      
                      //history.pushState(null, null, '?tab='+tid);
                      
                      
                });
            }
    }
    */
    
    /*
    function stateChangeTab()
    {
        //console.log('statechangetab');
        
        var tid = getUrlParameter('tab');
        
        if(tid){ 
            //console.log(tid); 
            //$('#'+tb).click();
            processTabLink(tid);
        }
        else { 
            //console.log('nottb'); 
            var tid = $('.lc-tabs-link').eq(0).attr('id');
            //console.log(tid);
            if(tid){
                processTabLink(tid);
            }
        }
    }
    */
    
    
    /*
    //Reset and Render the bottom contextual menu
    function initContextMenu()
    {
        //console.log(contextMenuLinks);
        var cWrap = 'context-action-menu';
        $('#'+cWrap).remove();
        
        var html = '<div id="'+cWrap+'"><div id="global-action-btns"><a href="#" class="context-menu-open"><i class="icon-plus-circle"></i></a><a href="#" class="context-menu-close"><i class="icon-cancel-circle"></i></a></div>';
        
        html += '<div class="context-menu-menu-wrap">';
        for(var i in contextMenuLinks){
            html += '<div class="context-menu-link-wrap"><a href="'+contextMenuLinks[i].url+'"><i class="'+contextMenuLinks[i].iconClass+'"></i><span>'+contextMenuLinks[i].linkTitle+'</span></a></div>';
        }
        
        html += '</div></div>';
        
        $('.main-container').append(html);
        
        var mh = $('.context-menu-menu-wrap').height();
        //console.log(mh);
        $('.context-menu-menu-wrap').css('top', '-'+mh+'px');
        
        reseatContextMenu();
    
    }
    //Put the context menu in the correct place when the page is resized
    function reseatContextMenu()
    {
        var ww = $(window).width();
        var pw = $('.main-container').width();
        if(ww > pw){
            var di = (ww - pw)/2;
            $('#context-action-menu').css('right', di+'px');
        }
        else {
            $('#context-action-menu').css('right', 0);
        }
    }
    //Set the contextual menu links and start the render
    var contextMenuLinks = [];
    function setContextMenu(links = null)
    {
        contextMenuLinks = links;
        //Dummy data
        contextMenuLinks = [
            {iconClass: 'icon-plus-circle', linkTitle: 'File Upload', url: '/node/add/documents?matter=69&destination=matter/69'},
            {iconClass: 'icon-plus-circle', linkTitle: 'Create Matter', url: '/node/add/matter?destination=matters'}
        ];
            
        if(contextMenuLinks != null){
            
            initContextMenu();
        }
    }
    //Initiate the contextual menu links
    function initContextMenuEvents()
    {
        $('.main-container').on('click', 'a.context-menu-open', function(e){
            e.preventDefault();
            $(this).hide();
            $('a.context-menu-close').show();
            $('div.context-menu-menu-wrap').show();
        });
        $('.main-container').on('click', 'a.context-menu-close', function(e){
            e.preventDefault();
            $(this).hide();
            $('a.context-menu-open').show();
            $('div.context-menu-menu-wrap').hide();
        });
    }
    */
    
    
})(jQuery, window, Drupal, drupalSettings);

