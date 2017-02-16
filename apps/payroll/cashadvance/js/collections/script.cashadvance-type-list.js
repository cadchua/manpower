/**
 * list of cash advance types
 */
define(['script.config'], function(config){
	
	var CashAdvanceTypeList = function(){
		var self = this;
		this.types = [];
		this.loadTypes = $.Deferred();
		
		$.post(config.payrollAPI, {cmd: 'get-records', criteria: {cashadvance: 1}})
			.done(function(data){
				var data = JSON.parse(data);
				self.loadTypes.resolve(data.records);
		});
	};
	
	return CashAdvanceTypeList;

});