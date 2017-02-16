/**
 * module for the module grid
 */

define(['script.config'], function(config){
	var ModuleGrid = {
		id: '#module-grid',
		name: 'ModuleGrid',
		init: function(CategoryGrid){
			var self = this;
			self.el = $(self.id);
			self.categoryGrid = CategoryGrid;
			self.popupEl = $("#module-popup");
			self.form = $("#module-form");
			self.loadGrid();
			self.listenForCategory();
			
			self.form.submit(function(e){
				e.preventDefault();
				var moduleName = self.form.find('[name="module"]').val();
				var filename = self.form.find("[name='filename']").val();
				var sequence = self.form.find("[name='sequence']").val();
				self.addModule(moduleName, filename, sequence);
			});
		},
		listenForCategory: function(){
			var self = this;
			self.categoryGrid.grid.on('select', function(event){
				event.onComplete = function(){
					var category_id = event.recid;
					self.categoryID = category_id;
					self.grid.postData.category_id = self.categoryID;
					self.grid.url = config.moduleApiUrl;
					self.grid.reload();
				};
			});
		},
		addModule: function(moduleName, filename, sequence){
			var self = this;
			$.post(config.moduleApiUrl, 
					{cmd: 'add-module', category_id: self.categoryID, module_name: moduleName, filename: filename, sequence: sequence}
			).done(function(data){
				var data = JSON.parse(data);
				if (data.status == 'success') {
					self.grid.reload();

					noty({
						text : 'Successfully added module.',
						timeout : 3000,
						type : 'success',
						layout : 'topRight'
					});
				} else if (data.status == 'error') {
					noty({
						text : data.message,
						timeout : 3000,
						type : 'error',
						layout : 'topRight',
					});
			}
			});
		},
		openPopup: function(){
			var self = this;
			self.popupEl.dialog();
		},
		loadGrid: function(){
			var self = this;
			self.grid = self.el.w2grid({
				name: self.name,
				header: "Modules",
				show : {
					header : true,
					toolbar : true,
					toolbarCategory : false,
					toolbarColumns : false,
					toolbarSearch : false,
					toolbarReload : false,
					toolbarSave: true,
					toolbarDelete: true,
				},
				toolbar : {
					items : [ {
						type : 'button',
						id : 'add-module',
						caption : 'Add Module'
					},],
					onClick : function(event) {
						switch (event.target) {
						case 'add-module':
							self.openPopup();
							break;
						}
					}
				},
				postData: {
					type: 'modules',
				},
				columns: [
				          {field: 'menu_name', caption: 'Menu Name', size: '20%', editable: {type: 'text'}},
				          {field: 'file_location', caption: 'File Location', size: '20%', editable: {type: 'text'}},
				],
			});
			
		},
	};
	
	return ModuleGrid;
});