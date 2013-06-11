var define;
define(['underscore','backbone','models/page'], function(_, Backbone, PageModel){

  var PagesCollection = Backbone.Collection.extend({

    model: PageModel,

	url: '/application/api/pages'

  });

  // You don't usually return a collection instantiated
  return PagesCollection;

});