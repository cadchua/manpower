/**
 * model to keep track of employees and amounts
 */
define(['script.config'], function(config){
	var EmployeeList = {
		employees: [],
		accounts: {},
		search: function(options){
			var self = this;
			var results = $.Deferred();
			var params = {
					cmd: 'search-employees-cashadvances',
			};
			
			$.extend(params, options);
			
			$.get(config.cashAdvanceAPI,  params)
			.done(function(data){
				var data = JSON.parse(data);
				self.accounts.accounts = data.accounts;
				self.accounts.total = data.total;
				results.resolve(self.accounts);
				$(self).trigger('change');
			});
			
			return results;
		},
	};
	
	return EmployeeList;
});