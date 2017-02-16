/**
 * module grid module
 */
define(['script.config'], function(config){
	var ModuleGrid = {
		id: '#module-grid',
		name: 'ModuleGrid',
		init: function(workgroupGrid){
			var self = this;
			self.el = $(this.id);
			self.workgroupGrid = workgroupGrid;
			self.loadGrid();
			
			self.workgroupGrid.grid.on('select', function(event){
				event.onComplete = function(){
					var workgroupID = event.recid;
					self.grid.url = config.modulesApiUrl;
					self.grid.postData.workgroup_id = workgroupID;
					self.grid.reload();
				};
			});
		},
		loadGrid: function(){
			var self = this;
			self.grid = self.el.w2grid({
				name: self.name,
				postData: {
					type: 'modules-assignment',
				},
				header: 'Modules',
				show: {
					toolbar: true,
					toolbarSave: true,
					toolbarColumns: false,
					toolbarReload: false,
					toolbarSearch: false,
					header: true,
				},
				columns: [
				          {field: 'menu_name', caption: 'Module', size: '20%'},
				          {field: 'assigned', caption: 'Assign', size:'20%', editable: {
				        	  type: 'checkbox',
				          }},
				          ]
			});
		}
	};
	
	return ModuleGrid;
});