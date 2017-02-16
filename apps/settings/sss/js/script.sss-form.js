/**
 * sss form module
 */
define(['script.config', 'lodash'], function(config, _){
	var SSSForm = {
			id: '#sss-form-wrap',
			name: 'SSSForm',
			init: function(SSSGrid){
				var self = this;
				self.el = $(self.id);
				self.SSSGrid = SSSGrid;
				self.url = config.sssApiUrl;
				self.bracketNo = null;
				self.fieldNames = [
								'bracket_no',
								'salary',
								'monthly_credit',
								'employer_share',
								'employee_share',
								'total_contribution',
							];
				self.fields = [];
				self.loadForm();
				self.listenToGrid();
			},
			loadForm: function(){
				var self = this;
				self.form = $('#sss-form');
				var saveBtn = self.form.find('button[name="save"]');
				var resetBtn = self.form.find('button[name="reset"]');
				_.each(self.fieldNames, function(fieldName){
					var currField = self.form.find('input[name="'+ fieldName +'"]');
					self.fields.push(currField);
				});

				saveBtn.click(function(){
					var fieldValues = {};
					
					_.each(self.fields, function(field){
						fieldValues[field.attr('name')] = field.val();
					});
					
					if(!self.bracketNo){
						self.createEntry(fieldValues);
					} else {
						self.saveEntry(fieldValues);
					}
				});
				
				resetBtn.click(function(){
					self.form.find('input').val('');
					self.bracketNo = null;
				});
			},
			createEntry: function(fields){
				var self = this;
				$.post(config.sssApiUrl, {cmd: 'save-record', record: fields, type: 'create'})
					.done(function(data){
						var data = JSON.parse(data);
						
						if(data.status == 'success'){
							self.SSSGrid.grid.reload();
						}
					});
			},
			saveEntry: function(fields){
				var self = this;
				$.post(config.sssApiUrl, {cmd: 'save-record', record: fields, type: 'save'})
					.done(function(data){
						var data = JSON.parse(data);
						if(data.status == 'success'){
							self.SSSGrid.grid.reload();
						}
					});
			},
			listenToGrid: function(){
				var self = this;
				
				self.SSSGrid.grid.on('select', function(event){
					event.onComplete = function(){
						var bracketNo = event.recid;
						self.bracketNo = bracketNo;
						self.loadRecord(self.bracketNo);
					};
				});
				
				self.SSSGrid.grid.on('unselect', function(event){
					event.onComplete = function(){
						self.bracketNo = null;
					};
				});
				
			},
			loadRecord: function(bracketNo){
				var self = this;
				
				$.post(config.sssApiUrl, {cmd: 'get-record', bracket_no: bracketNo})
					.done(function(data){
						var data = JSON.parse(data);
						
						if(data.status == 'success'){
							_.each(self.fields, function(field){
								field.val(data.entry[field.attr('name')]);
							});
						}
					});
			},
	};
	return SSSForm;
});