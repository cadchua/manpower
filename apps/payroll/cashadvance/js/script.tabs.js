/**
 * module for putting everything in a tab
 */
define(['script.config'], function(config){
	var Tabs = {
			id: '#cash-tabs',
			init: function(){
				var self = this;
				
				self.el = $(self.id);
				
				self.el.tabs();
			},
	};
	
	return Tabs;
});