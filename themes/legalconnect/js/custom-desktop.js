(function($, w, Drupal, drupalSettings){
    
    
    //NOT USED - MATTER LEFT SIDEBAR CLICKS ON DESKTOP
    var DesktopMatter = new Class(w.luna);
    
    DesktopMatter.extend({
        
        initialize: function(){
            this.baseUrl = drupalSettings.legalc.baseUrl;
            $('.main-container').on('click', this.proxy(this.matterClicked));
        },
        matterClicked: function(e){
            e.preventDefault();
            var mid = parseInt( $(e.target).attr('data-matter-nid') );
            this.processMatterClicked(mid);
        },
        processMatterClicked: function(mid){
            
            $.ajax({
                    method: "POST",
                    url: this.baseUrl+""
                    //data: { name: "John", location: "Boston" }
                  })
                    .done(this.proxy(this.processTabLinkDone));
        }
        
    });
    w.myDesktopMatter = DesktopMatter;
    
    $(document).ready(function(){
        console.log('custom-desktop.js');
    });
    
    
})(jQuery, window, Drupal, drupalSettings);