define(['jquery','underscore','backbone',
  'text!'+window.FrontPlateAdmin.base+'/templates/media/overview.html'
], function($, _, Backbone, overviewTemplate){

  var MediaOverview = Backbone.View.extend({

    // We append the login to the body...
    el: '#content',

    /*events: {
      'click #attempAuth':'login'
    },*/

    render: function(){

      var compiledTemplate = _.template( overviewTemplate );

      this.$el.html( compiledTemplate );

        // Remove active menu item
        $('#main-nav ul li').removeClass('selected');

        // Remove active menu item
        $("#main-nav ul li[data-item='media']").addClass('selected');

    },

    destroy: function(){

      this.$el.html( '' );

    }

  });

  return MediaOverview;

});