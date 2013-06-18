define(['jquery','underscore','backbone','views/frame',
  'text!'+window.FrontPlateAdmin.base+'/templates/auth/login.html',
  'text!'+window.FrontPlateAdmin.base+'/templates/parts/site-icon-block.php'
], function($, _, Backbone, FrameView, loginTemplate, siteIconBlock){

  var LoginView = Backbone.View.extend({

    // We append the login to the body...
    el: 'body',

    events: {
      'click #attempAuth':'login'
    },

    render: function(){

      var compiledTemplate = _.template( loginTemplate, {site_icon_block:siteIconBlock} );

      this.$el.html( compiledTemplate );

      setTimeout(function(){

        $('.login').removeClass('login-fadeUp');

      }, 1000);

        $('#loginForm input[type="text"]').focus();

    },

    destroy: function(){

      $('.login').css({'opacity':'0'});

      setTimeout(function(){

        $('.login').remove();

      }, 500);

    },

    /* Event Handler Functions */

      login: function(event){

        event.preventDefault();

        $('#loginForm').css({'opacity':'0','height':'0px'});

        $('.login').css({'height':'250px','margin-top:':'-125px'});

        $('.login').append('<div class="ui-loading"><ul><li></li><li></li><li></li></ul></div>');

        var thisView = this;

        setTimeout(function(){
        $.ajax({
            url:'api/auth',
            type:'POST',
            dataType:"json",
            data: { username: $('#loginForm input[type="text"]').val(), password: $('#loginForm input[type="password"]').val() },
            success:function (data) {

                if(data.status === true) {

                    thisView.destroy();

                    var frame = new FrameView();
                        frame.render();

                        window.FrontPlateAdmin.session = data.session;

                    Backbone.history.navigate('pages', {trigger: true, replace: true});

                }else{

                  $('.login').css({'height':'318px','margin-top:':'-159px'});

                  setTimeout(function(){ $('#loginForm').css({'opacity':'1','height':'auto'}); }, 350);

                  $('.login .ui-loading').remove();

                  $('.login').addClass('login-error');

                  setTimeout(function(){

                   $('.login').removeClass('login-error');

                  }, 1500);

                  $('#loginForm input[type="text"]').focus();

                }
            }
        });

        }, 500);

      }

  });

  return LoginView;

});