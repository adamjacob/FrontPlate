define(['underscore','backbone'], function(_, Backbone){

  var PageModel = Backbone.Model.extend({

    defaults: {
      title: "Yo New Page",
      slug: "/yo-new-page",
      template: "page",
      meta: {
      	{
      		key: 'body',
      		data: 'My new page stuff here...'
      	}
      }
    }

  });

  // Return the model for the module
  return PageModel;

});