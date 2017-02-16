/**
 * list of branches based on orange hrm locations
 */
define(['script.config'], function(config){
	var BranchList = function(options){
		var self = this;
		self.branches = [];

		this.loadBranches = $.Deferred();

		function loadBranches_(){
			return $.post(config.systemConfigAPI, {cmd: 'locations'})
				.done(function(data){
					var data = JSON.parse(data);
					self.branches = data.data;
					self.loadBranches.resolve(self.branches);
				}).fail(function(data){
					var data = JSON.parse(data);
					self.loadBranches.reject(data);
				});
		}

		loadBranches_();
	};

	return BranchList;
});
