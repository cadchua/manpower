/**
 * model for branch list
 */
define(['script.config', '../models/script.cashadvance-list-controls-model'], function(config, CAListControlsModel){
	var BranchListView = function(options){
		var self = this;

		$.extend(this, options);
		self.el = $(this.id);
		self.branchList.loadBranches.done(function(branches){
			var option = $('<option>');
			self.el.length = 1;
			branches.forEach(function(branch){
				var clone = option.clone()
					.html(branch.name)
					.val(branch.id)
					.appendTo(self.el);
			});
		});
		self.el.val(CAListControlsModel.attributes.branch);

		self.el.off('change');
		self.el.on('change', function(){
			CAListControlsModel.set({
				branch: self.el.val(),
			});
		});
		return self;
	};

	return BranchListView;
});
