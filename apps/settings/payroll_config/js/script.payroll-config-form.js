/**
 * sss form module
 */
define(['script.config', 'lodash'], function(config, _){
	var PayrollConfigForm = {
			id: '#payroll-config-form',
			name: 'PayrollConfigForm',
			popupID: '#payconfig-popup',
			init: function(PayrollConfigGrid){
				var self = this;
				self.el = $(self.id);
				self.PayrollConfigGrid = PayrollConfigGrid;
				self.url = config.payrollApiUrl;
				self.payconfigID = null;
				self.govReqCodeGroup = '077';
				self.payCategoryGroup = '034';
				self.govReqCodes = {};
				self.fieldNames = [
								'pay_name',
								'short_name',
								'income',
								'pay_category',
								'taxable',
								'tax_deductible',
								'allowance',
								'loan',
								'government_req',
								'daily_limit',
								'max_limit',
								'cashadvance',
							];
				self.popup = $(self.popupID);
				self.fields = [];
				self.loadForm();
				self.listenToGrid();
			},
			show: function(title){
				var self = this;
				
				self.popup.dialog({
					width: 600,
					modal: true,
					title: title,
				});
			},
			loadGovRequirements: function(){
				var self = this;
				var option = $('<option>');
				self.govReqField.html('');
				var defaultOption = option.clone()
										.val('')
										.html('-select government requirement-')
										.appendTo(self.govReqField);
				$.get(config.systemConfigsUrl, {cmd: 'get-group-codes', group_id: self.govReqCodeGroup})
					.done(function(data){
						var data = JSON.parse(data);
						_.each(data.codes, function(code){
							var currField = option.clone();
							currField.val(code.config_id)
								.html(code.config_name)
								.appendTo(self.govReqField);
						});
					});
			},
			loadPaymentCategories: function(){
				var self = this;
				var option = $('<option>');
				self.paymentCategoriesField.html('');
				var defaultOption = option.clone()
										.val('')
										.html('-select payment category-')
										.appendTo(self.paymentCategoriesField);
				
				$.get(config.systemConfigsUrl, {cmd: 'get-group-codes', group_id: self.payCategoryGroup})
				.done(function(data){
					var data = JSON.parse(data);
					_.each(data.codes, function(code){
						var currField = option.clone();
						currField.val(code.config_id)
							.html(code.config_name)
							.appendTo(self.paymentCategoriesField);
					});
				});
				
			},
			loadForm: function(){
				var self = this;
				self.form = $(self.id);
				var saveBtn = self.form.find('button[name="save"]');
				var resetBtn = self.form.find('button[name="reset"]');
				var incomeEl = self.form.find('[name="income"]');
				var loanEl = self.form.find('[name="loan"]');

				_.each(self.fieldNames, function(fieldName){
					var currField = self.form.find('[name="'+ fieldName +'"]');
					self.fields.push(currField);
				});
				self.govReqField = self.form.find('[name="government_req"]');
				self.paymentCategoriesField = self.form.find('[name="pay_category"]');
				self.loadPaymentCategories();
				self.loadGovRequirements();

				saveBtn.click(function(){
					var fieldValues = {};
					
					_.each(self.fields, function(field){
						if(field.attr('type') == 'checkbox'){
							fieldValues[field.attr('name')] = field.prop('checked');
						} else {
							fieldValues[field.attr('name')] = field.val();
						}
					});
					
					if(!self.payconfigID){
						self.createEntry(fieldValues);
					} else {
						self.saveEntry(fieldValues);
					}
				});
				
				// disabled by default 
				self.form.find('[name="daily_limit"]').prop('disabled', true);
				self.form.find('[name="max_limit"]').prop('disabled', true);
				
				self.form.find('[name="taxable"]').prop('disabled', true);
				self.form.find('[name="tax_deductible"]').prop('disabled', false);
				self.form.find('[name="allowance"]').prop('disabled', true);
				self.form.find('[name="loan"]').prop('disabled', false);
				self.form.find('[name="government_req"]').prop('disabled', false);
				
				resetBtn.click(function(){
					self.form.find('input').val('');
					self.payconfigID = null;
				});
				
				incomeEl.change(function(){
					var isIncome = $(this).prop('checked');
						self.form.find('[name="taxable"]').prop('disabled', !isIncome);
						self.form.find('[name="tax_deductible"]').prop('disabled', isIncome);
						self.form.find('[name="allowance"]').prop('disabled', !isIncome);
						self.form.find('[name="loan"]').prop('disabled', isIncome);
						self.form.find('[name="government_req"]').prop('disabled', isIncome);
						
						if(!loanEl.prop('checked') && !isIncome){
							self.form.find('[name="cashadvance"]').prop('disabled', false);
						} else {
							self.form.find('[name="cashadvance"]').prop('disabled', true);
						}
				});
				
				loanEl.change(function(){
					var isLoan = $(this).prop('checked');
					self.form.find('[name="daily_limit"]').prop('disabled', !isLoan);
					self.form.find('[name="max_limit"]').prop('disabled', !isLoan);
					
					if(!isLoan && !incomeEl.prop('checked')){
						self.form.find('[name="cashadvance"]').prop('disabled', false);
					} else {
						self.form.find('[name="cashadvance"]').prop('disabled', true);
					}
				});

			},
			createEntry: function(fields){
				var self = this;
				$.post(config.payrollApiUrl, {cmd: 'save-record', record: fields, type: 'create'})
					.done(function(data){
						var data = JSON.parse(data);
						
						if(data.status == 'success'){
							self.PayrollConfigGrid.grid.reload();
						}
					});
			},
			saveEntry: function(fields){
				var self = this;
				$.post(config.payrollApiUrl, {cmd: 'save-record', record: fields, type: 'save', payconfig_id: self.payconfigID})
					.done(function(data){
						var data = JSON.parse(data);
						if(data.status == 'success'){
							self.PayrollConfigGrid.grid.reload();
						}
					});
			},
			listenToGrid: function(){
				var self = this;
				self.PayrollConfigGrid.grid.on('select', function(event){
					event.onComplete = function(){
						var payconfigID = event.recid;
						self.payconfigID = payconfigID;
						self.loadRecord(self.payconfigID);
						self.show('Edit Payroll Config');
					};
				});
				
				self.PayrollConfigGrid.onNew(function(){
					self.payconfigID = null;
					_.each(self.fields, function(field){
						if(field.attr('type') == 'checkbox'){
							field.prop('checked', false);
						} else {
							field.val('');
						}
					});
					
					self.show('New Payroll Config');
				});
				
			},
			loadRecord: function(payconfigID){
				var self = this;
				
				$.post(config.payrollApiUrl, {cmd: 'get-record', payconfig_id: payconfigID})
					.done(function(data){
						var data = JSON.parse(data);
						
						if(data.status == 'success'){
							
							_.each(self.fields, function(field){
								field.prop('disabled', false);
							});
							
							_.each(self.fields, function(field){

								if(field.attr('type') == 'checkbox'){
									field.prop('checked', data.entry[field.attr('name')]);
								} else {
									field.val(data.entry[field.attr('name')]);
								}
								
								
							});
						}
					});
			},
	};
	return PayrollConfigForm;
});