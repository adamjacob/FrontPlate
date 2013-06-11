var define;
define(['jquery','underscore','backbone',
  'text!/application/admin/templates/pages/edit.php'
], function($, _, Backbone, editTemplate){

  var PageEdit = Backbone.View.extend({

    events: {

        'click #saveModel':'saveThisStuff'

    },

    initialize: function(){

        // Bind to this collection...
        this.model.on('change', this.render, this);

        $('#loading').css({'display':'block'});

    },

    render: function(){

        var self = this;

        // See what this template requires!
        $.get('/application/api/template_values/'+this.model.get('template'), function(data){

             var compiledTemplate = _.template( editTemplate, { page: self.model.toJSON(), template_meta: data } );

            self.$el.html( compiledTemplate );

            // Done loading...hide
            $('#loading').css({'display':'none'});

            return self;

        }, 'json');

    },

    saveThisStuff: function(event){

        event.preventDefault();

        this.model.set({ title:$('#page_attributes input[name="title"]').val(), slug:$('#page_attributes input[name="slug"]').val(), template:$('#page_attributes select[name="template"]').find(":selected").val() });

        var metas = [];

        _.each($('#page_metas').find('input, select, textarea'), function(meta_item){

            var type = 'text';

                if($(meta_item).is("input")){

                    type = 'string';

                }

            metas.push({ "name": $(meta_item).attr('name'), "value": $(meta_item).val(), "type": type });

        });

        this.model.set({'meta':metas});

        this.model.save();

            Backbone.history.navigate('pages', {trigger: true});

    },

    destroy: function(){

      this.$el.html( '' );

    }

  });

  return PageEdit;

});