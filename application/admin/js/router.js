var define, console;
define(['jquery','underscore','backbone','require'
], function($, _, Backbone, require){

    var Router = Backbone.Router.extend({
      routes: {
        // Define some URL routes
        'login': 'login',
        'pages': 'pages',
        'page/:id': 'page',
        'media': 'media',

        // Default
        '*actions': 'defaultAction'
      },

      login: function(){

        require(['views/auth/login'], function(LoginView){

          var login = new LoginView();

            login.render();

        });

      },

      // Single Page
      page: function(page_id){

        require(['views/pages/edit','models/page'], function(PageEdit, PageModel){

            var page = new PageModel({ id: page_id });

            var editView = new PageEdit({model:page});

                page.fetch({success: function(){

                    editView.render();

                    // Add to the content element...
                    $('#content').html( editView.el );

                    }
                });

        });

      },

      // Overview Page
      pages: function(id){

        require(['views/pages/overview','collections/pages'], function(PagesOverview, PagesCollection){

          var pages = new PagesCollection();

          var overview = new PagesOverview({collection: pages});

          console.log('Pages View...');

        });

      },

      media: function(){

        require(['views/media/overview'], function(MediaOverview){

          var overview = new MediaOverview();

            overview.render();

          console.log('Media View...');

        });

      },

      defaultAction: function(actions){

        console.log('No route:', actions);

      }

    });

      var initialize = function(){

        // Start our router...
        var app_router = new Router;

        // Enable push state
        Backbone.history.start({pushState:true,root:'/admin/'});

        // Catch any auth errors an redirect back to login!
        $.ajaxSetup({
          statusCode: {
            401: function(){

              Backbone.history.navigate('login', {trigger: true, replace: true});

            }
          }
        });

      };

    return { initialize: initialize };

});