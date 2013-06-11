var define;
define(['jquery','underscore','backbone',
  'text!/application/admin/templates/frame.html',
  'text!/application/admin/templates/parts/site-icon-block.html'
], function($, _, Backbone, frameTemplate, siteIconBlock){

  var FrameView = Backbone.View.extend({

    events: {
      'click #openPagesView':'navPages',
      'click #openMediaView':'navMedia'
    },

    navPages: function(){

      Backbone.history.navigate('pages', {trigger:true});

    },

    navMedia: function(){

      Backbone.history.navigate('media', {trigger:true});

    },

    render: function(){

      var compiledTemplate = _.template( frameTemplate, {site_icon_block:siteIconBlock} );

      this.$el.html( compiledTemplate );

      $('body').html(this.el);

      $('.sidebar-menu, .content-area').css({'opacity':'1'});

    },

  });

  return FrameView;

});