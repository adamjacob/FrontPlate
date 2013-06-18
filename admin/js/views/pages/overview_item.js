define(['jquery','underscore','backbone',
  'text!'+window.FrontPlateAdmin.base+'/templates/pages/overview_item.html'
], function($, _, Backbone, overviewItemTemplate){

  var PagesOverviewItem = Backbone.View.extend({

    tagName: 'li',

    events: {
      'click':'editPage'
    },

    initialize: function(){

        this.model.on('change', this.render, this);
        this.model.on('destroy hide', this.remove, this);

    },

    render: function(){

        this.$el.html( _.template( overviewItemTemplate, { item: this.model.attributes } ) );

        return this;

    },

    editPage: function(){

      Backbone.history.navigate('page/'+this.model.get('id'), {trigger: true});

    },

    destroy: function(){

      this.$el.remove();

    }

  });

  return PagesOverviewItem;

});