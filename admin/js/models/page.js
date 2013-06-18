define(['underscore','backbone'], function(_, Backbone){

  var PageModel = Backbone.Model.extend({
    
    defaults: {
    	title: 'Untitled Page',
    	meta: {}
    },

    urlRoot: window.FrontPlateAdmin.base + 'api/page'

  });

  // Return the model for the module
  return PageModel;

});