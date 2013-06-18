define(['underscore','backbone','models/page'], function(_, Backbone, PageModel){

  var PagesCollection = Backbone.Collection.extend({

    model: PageModel,

	url: window.FrontPlateAdmin.base + 'api/pages'

  });

  // You don't usually return a collection instantiated
  return PagesCollection;

});