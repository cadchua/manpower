/**
 * grid for sss 
 */
define(['script.config', 'lodash'], function(config, _){
	var PayrollConfigGrid = {
			id: '#payconfig-grid',
			name: 'PayrollConfigGrid',
			init: function(){
				var self = this;
				self.newCallbacks = [];
				self.el = $(self.id);
				self.loadGrid();
			},
			onNew: function(callback){
				var self = this;
				
				self.newCallbacks.push(callback);
			},
			loadGrid: function(){
				var self = this;
				
				self.grid = self.el.w2grid({
					name: self.name,
					url: config.payrollApiUrl,
					header: "Payroll",
					show: {
						header: true,
						toolbar: true,
						toolbarSearch: false,
						toolbarColumns: false,
						toolbarReload: false,
					},
					toolbar: {
						items: [
						        {type: 'button', id: 'new-payconfig-btn', caption: 'New Payroll Config'}
						        ],
						onClick: function(target){
							var payconfigID = self.grid.getSelection()[0];
							if(target.target == 'new-payconfig-btn'){
								_.each(self.newCallbacks, function(callback){
									callback(payconfigID);
								});
							}
						},
					},
					columns: [
					   {field: 'pay_name', caption: 'Pay Name', size: '20%'},
					   {field: 'short_name', caption: 'Short Name', size: '20%',},
					   {field: 'pay_category_name', caption: 'Pay Category', size: '20%',},
					   {field: 'government_req_name', caption: 'Government Requirement', size: '20%'},
					   {field: 'income', caption: 'Income', size: '20%', render: 'toggle'},
					   {field: 'taxable', caption: 'Taxable', size: '20%', render: 'toggle'},
					   {field: 'tax_deductible', caption: 'Tax Deductible', size: '20%', render: 'toggle'},
					   {field: 'allowance', caption: 'Allowance', size: '20%', render: 'toggle'},
					   {field: 'cashadvance', caption: 'Cash Advance', size: '20%', render: 'toggle'},
					   {field: 'loan', caption: 'Loan', size: '20%', render: 'toggle'},
					   {field: 'daily_limit', caption: 'Daily Limit', size: '20%',},
					   {field: 'max_limit', caption: 'Max Limit', size: '20%', render: 'number:2'},
					]
				});
			},
	}
	
	return PayrollConfigGrid;
});