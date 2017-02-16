/**
 * module for customer cashadvance accounts
 */
define(['script.config', 
        'models/script.cashadvance-list-controls-model',
        'models/script.cashadvance-transaction-model'], function(config, CAListControlsModel, CATransactionModel){
	var CashadvanceAccounts = {
			id: '#ca-list-grid',
			name: 'CashadvanceAccounts',
			init: function(){
				var self = this;
				
				self.el = $(this.id);
				self.loadGrid();
			},
			refresh	: function(){
				this.grid.reload();
			},
			loadGrid: function(){
				var self = this;
				
				self.grid = self.el.w2grid({
					name: self.name,
					url: config.cashAdvanceAPI,
					postData: {
						type: 'accounts',
					},
					show: {
						header: true,
					},
					header: 'Accounts',
					columns: [
					          {field: 'tr_date', caption: 'Date', size: '20%'},
					          {field: 'pay_name', caption: 'Tr. Type', size: '20%'},
					          {field: 'debit', caption: 'Debit', size: '20%'},
					          {field: 'credit', caption: 'Credit', size: '20%'},
		            ],
		            onSelect: function(event){
		            	event.onComplete = function(){
		            		var ca = this.get(event.recid);
		            		CATransactionModel.set(ca);
		            	};
		            },
				});
				
				$(CAListControlsModel).on('change', function(){
					$.extend(self.grid.postData, this.attributes);
				});
				
				$(CATransactionModel).on('change', function(){
					$('#ca-hold-chk').prop('checked', this.attributes.hold == true);
				});
				
				$('#ca-hold-chk').on('change', function(){
					CATransactionModel.set({
						hold: $(this).prop('checked'),
					});
					
					CATransactionModel.saveHold()
						.done(function(){
							self.grid.reload();
						});
				});
				
				$('#ca-display-btn').click(function(){
					CAListControlsModel.updateBalance();
					self.grid.reload();
				});
			},
	};
	
	return CashadvanceAccounts;
});