var define;define(["jquery","underscore","backbone","text!/application/admin/templates/pages/edit.html"],function(e,t,n,r){var i=n.View.extend({events:{"click #saveModel":"saveThisStuff"},initialize:function(){this.model.on("change",this.render,this);e("#loading").css({display:"block"})},render:function(){var n=t.template(r,this.model.toJSON());this.$el.html(n);e("#loading").css({display:"none"})},saveThisStuff:function(t){t.preventDefault();this.model.set({title:e('input[name="title"]').val()});this.model.save()},destroy:function(){this.$el.html("")}});return i});