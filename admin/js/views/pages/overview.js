define(['jquery','underscore','backbone',
  'collections/pages', 'models/page', 'views/pages/overview_item'
], function($, _, Backbone, PagesCollection, PageModel, PagesOverviewItem){

  var PagesOverview = Backbone.View.extend({
    
    tagName: 'ul',
    className: 'content-list',

    initialize: function(){

        // Setup some events...
        this.collection.on('add', this.addOne, this);
        this.collection.on('reset', this.render, this);

        // Fetch the collection from the server
        this.collection.fetch();

        // Show our loading item...
        $('#loading').css({'display':'block'});

    },

    render: function(){

        // Render each view...
        this.collection.forEach(this.addOne, this);

        // Remove active menu item
        $('#main-nav ul li').removeClass('selected');

        // Remove active menu item
        $("#main-nav ul li[data-item='pages']").addClass('selected');

        // Add this thing to the content view...
        $('#content').html( this.$el );

        // Hide the loading item...we are done
        $('#loading').css({'display':'none'});

        $("#main-nav ul li[data-item='pages'] .add").unbind();

        var self = this;

        $("#main-nav ul li[data-item='pages'] .add").click(function(){

            self.createItem();

        });

    },

    createItem: function(){

        var newItem = new PageModel();

            this.collection.add(newItem);

            newItem.save();

    },

    addOne: function(item){

        // Create a new view...pass the model
        var itemView = new PagesOverviewItem({model: item});

        // Append it to this.el
        this.$el.append(itemView.render().el);

    }

  });

  return PagesOverview;

});