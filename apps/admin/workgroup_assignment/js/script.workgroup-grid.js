/**
 * workgroup grid module
 */

define(['script.config'], function(config){
	var WorkgroupGrid = {
		id: '#workgroup-grid',
		name: 'WorkgroupGrid',
		init: function(){
			var self = this;
			self.el = $(this.id);
			self.loadGrid();
		},
		loadGrid: function(){
			var self = this;
			
			self.grid = self.el.w2grid({
				name: self.name,
				url: config.modulesApiUrl,
				postData: {
					type: 'workgroups',
				},
				header: 'Workgroups',
				show: {
					header: true,
				},
				columns: [
				          {field: 'workgroup_name', caption: 'Workgroup', size: '20%'},
				          ],
			});
		},
	};
	return WorkgroupGrid;
});