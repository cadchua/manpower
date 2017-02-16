/**
 * category grid
 */
define([ 'script.config' ],
		function(config) {
			var CategoryGrid = {
				id : '#category-grid',
				name : 'CategoryGrid',
				init : function() {
					var self = this;
					self.el = $(self.id);
					self.popupEl = $('#category-popup');
					self.form = $('#category-form');
					self.loadGrid();

					self.form.submit(function(e) {
						e.preventDefault();
						var categoryName = self.form.find('[name="category"]')
								.val();
						var sequence = self.form.find('[name="sequence"]')
								.val();
						if (self.categoryID === null) {
							self.addCategory(categoryName, sequence);
						}
						else {
							self.saveCategory(categoryName, sequence);
						}
					});
				},
				saveCategory : function(categoryName, sequence) {
					var self = this;
					$.post(config.moduleApiUrl, {
						cmd : 'save-category',
						category_id : self.categoryID,
						category_name : categoryName,
						sequence : sequence
					}).done(function(data) {
						var data = JSON.parse(data);

						if (data.status == 'success') {

							self.grid.reload();

							noty({
								text : 'Successfully saved category.',
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
				addCategory : function(categoryName, sequence) {
					var self = this;
					$.post(config.moduleApiUrl, {
						cmd : 'add-category',
						category_name : categoryName,
						sequence : sequence
					}).done(function(data) {
						var data = JSON.parse(data);

						if (data.status == 'success') {

							self.grid.reload();

							noty({
								text : 'Successfully added category.',
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
				openPopup : function(action) {
					var self = this;
					switch (action) {
					case 'add':
						self.categoryID = null;
						break;
					case 'save':
						var selected = self.grid.get(self.grid.getSelection()[0]);
						self.categoryID = selected.category_id;
						self.form.find('[name="category"]').val(
								selected.category_name);
						self.form.find('[name="sequence"]').val(
								selected.sequence);
						break;
					}
					self.popupEl.dialog();
				},
				loadGrid : function() {
					var self = this;
					self.grid = self.el.w2grid({
						name : self.name,
						url : config.moduleApiUrl,
						header : "Categories",
						show : {
							header : true,
							toolbar : true,
							toolbarCategory : false,
							toolbarColumns : false,
							toolbarSearch : false,
							toolbarReload : false,
							toolbarDelete: true,
						},
						toolbar : {
							items : [ {
								type : 'button',
								id : 'add-category',
								caption : 'Add Category'
							}, {
								type : 'button',
								id : 'edit-category',
								caption : 'Edit Category'
							}, ],
							onClick : function(event) {
								switch (event.target) {
								case 'add-category':
									self.openPopup('add');
									break;
								case 'edit-category':
									self.openPopup('save');
									break;
								}
							}
						},
						postData : {
							type : 'categories'
						},
						columns : [ {
							field : 'category_name',
							caption : 'Category',
							size : '100%',
						}, ]
					});
				}
			};

			return CategoryGrid;
		});