require.config({

	paths: {
		jquery:		'lib/jquery',
		underscore: 'lib/underscore',
		backbone:	'lib/backbone',
		text:		'lib/text'
	},

	shim: {

		'underscore': {
			exports:	'_'
		},

		'backbone': {
			deps:		['jquery','underscore'],
			exports:	'Backbone'
		}

	}

});

require(['jquery','app'], function($, App){

	$(function(){

		return App.init();

	});

});
