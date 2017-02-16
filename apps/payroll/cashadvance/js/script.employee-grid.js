/**
 * grid for searching employees
 */
define(['script.config',
        'collections/script.cashadvance-type-list',
        'views/script.cashadvance-type-list-view',
        'models/script.employee-info-model',
        'models/script.list-control-model',
        'collections/script.employee-list',
        'models/script.cashadvance-list-controls-model',
        'collections/script.branch-list',
        'views/script.branch-list-view'],
        function(config,
                 CashAdvanceTypeList,
                 CashAdvanceTypeListView,
                 EmployeeInfo,
                 ListControlModel,
                 EmployeeList,
                 CAListControlsModel,
                 BranchList,
                 BranchListView){
	var EmployeeGrid = {
		id: '#employee-grid',
		name: 'EmployeeGrid',
		init: function(){
			var self = this;
			self.el = $(this.id);
			self.saveBtn = $('#save-ca-btn');
			self.url = config.employeeAPI;
      self.branchList = new BranchList();

			self.cashAdvanceTypeList = new CashAdvanceTypeList();

			self.loadGrid();
		},
		loadEmployees: function(){
			var self = this;
			EmployeeList.search(ListControlModel.getAttributes())
			.done(function(accounts){
				self.grid.records = accounts.accounts;
				self.grid.total = accounts.total;
				self.grid.refresh();
			});
		},
		loadGrid: function(){
			var self = this;

			self.grid = self.el.w2grid({
				name: self.name,
				url: config.cashAdvanceAPI,
				columns: [
				          {field: 'name', caption: 'Name', size: '100%'},
				          {field: 'balance', caption: 'Amount', size: '100%', editable: {
				        	  type: 'float',
				          }},
				          ],
				show: {
					toolbar: true,
					toolbarReload: false,
					toolbarColumns: false,
					toolbarSearch: false,
					header: true,
				},
				header: 'List of Employees',
				toolbar: {
					items: [
					        {type: 'html', html: '<input type="text" id="employee-search-txt">'},
					        {type: 'html', html: '<select id="employee-branch"><option value="">-SELECT BRANCH-</option></select>'},
					        {type: 'html', html: '<select id="cash-advance-type"><option value="">-CASH ADVANCE TYPE-</option></select>'},
					        {type: 'html', html: '<input type="text" id="list-date" placeholder="SELECT DATE">'},
					        {type: 'button', id: 'employee-search', caption: 'Search'}
					        ],
				    onClick: function(target, data){
				    	switch(target){
				    	case 'employee-search':
			    			self.loadEmployees.call(self);
				    		break;
				    	}
				    },
				},
				onRefresh: function(event){
					event.onComplete = function(){
            $(self.branchListView).off('change');
            self.branchListView = new BranchListView({
              id: '#employee-branch',
              branchList: self.branchList,
            });
            $(self.branchListView).on('change', function(event, selectedBranch){
              ListControlModel.set({
                branch: selectedBranch,
              });
            });

						$(self.cashAdvanceTypeListView).off('change');
						self.cashAdvanceTypeListView = new CashAdvanceTypeListView({
							id: '#cash-advance-type',
							cashAdvanceTypeList: self.cashAdvanceTypeList,
						});

						$(self.cashAdvanceTypeListView).on('change', function(event, selectedType){
							ListControlModel.set({
								cashAdvanceType: selectedType,
							});
						});

						// intialize date picker
						$('#list-date').datepicker({
							dateFormat: 'yy-mm-dd',
						}).on('change', function(){
							ListControlModel.set({ date: $(this).val() });
						});

						$('#employee-search-txt, #employee-branch').on('change', function(){
							ListControlModel.set({
								employeeName: $('#employee-search-txt').val(),
								branch: $('#employee-branch').val(),
							});
						});

						$('#cash-advance-type').val(ListControlModel.attributes.cashAdvanceType);
						$('#employee-search-txt').val(ListControlModel.attributes.employeeName);
						$('#list-date').val(ListControlModel.attributes.date);
					};
				},
				onSelect: function(event){
					event.onComplete = function(){
						var employee = this.get(event.recid);
						EmployeeInfo.setId(employee.employee_id);
						CAListControlsModel.set({
							employee_id: employee.employee_id,
						});
					};
				},
				onSave: function(event){
					event.onComplete = function(){
						var validation = noty({
							text: 'Successfully saved cash advances.',
							timeout: 1000,
							layout: 'topRight',
							type: 'success',
						});
						self.loadEmployees.call(self);
					};
				},
			});


			$(ListControlModel).on('change', function(){
				$.extend(self.grid.postData, this.getAttributes());
			});


			self.saveBtn.click(function(){
				var validation = ListControlModel.validate();
				if(typeof validation == 'string'){
					var notification = noty({
						text: validation,
						timeout: 1000,
						type: 'error',
						layout: 'topRight',
					})
				} else {
					self.grid.save();
				}
			});
		},
	};

	return EmployeeGrid;
})
