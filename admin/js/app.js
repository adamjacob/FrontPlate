
var define, console; // So we can pass lint...

define(['jquery','underscore','backbone','router', 'views/frame'],

    function($, _, Backbone, Router, FrameView){

      var init = function(){

      	Router.initialize();
      	
	      	// Lets get this thing pop'n
		    if(window.FrontPlateAdmin.session.length != 0){
		      
		      // Build our application frame...
		      var frame = new FrameView();
		          frame.render();

		      // Active user...to pages overview we go
		      Backbone.history.navigate('pages', {trigger: true, replace: true});

		    }else{

		      // Whoh there buddy...you need to login
		      Backbone.history.navigate('login', {trigger: true, replace: true});

		    }

      };

      return {
        init: init
      };

  }

);