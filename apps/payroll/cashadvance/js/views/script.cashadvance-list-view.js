/**
 * view for cashadvance accounts popup
 */
define(['script.config',
        'collections/script.cashadvance-type-list',
        'views/script.cashadvance-type-list-view',
        'models/script.cashadvance-list-controls-model',
        'views/script.memo-view',
        'views/script.employee-info-view'], function(config,
        											 CashAdvanceTypeList,
        											 CashAdvanceTypeListView,
        											 CashAdvanceListControlsModel,
        											 MemoView,
        											 EmployeeInfoView){
	var CashAdvanceListView = {
		id: '#employee-ca-accounts-popup',
		title: 'Cash Advances',
		initialize: function(cashadvanceGrid){
			var self = this;
			self.el = $(self.id);
			self.viewBtn = $('#view-ca-btn');
			self.txtBalance = $('#ca-list-balance');
			self.cashadvanceGrid = cashadvanceGrid;
			self.employeeView = new EmployeeInfoView({
				id: '#employee-ca-info-container',
			});

			self.cashAdvanceTypeList = new CashAdvanceTypeList();
			self.cashAdvanceTypeListView = new CashAdvanceTypeListView({
				id: '#ca-list-cashadvance-type',
				cashAdvanceTypeList: self.cashAdvanceTypeList,
			});

			$(self.cashAdvanceTypeListView).on('change', function(event, selectedType){
				CashAdvanceListControlsModel.set({
					cashadvance_type: selectedType,
				});
			});

			$('#ca-from-txt').add('#ca-to-txt').change(function(){
				var from = $('#ca-from-txt').val();
				var to = $('#ca-to-txt').val();

				CashAdvanceListControlsModel.set({
					from: from,
					to: to
				});
			});

			// initialize datepickers
			self.el.find('.datepicker').datepicker({
				dateFormat: 'yy-mm-dd',
			});

			// listen to add dr/cr memo button
			$('#add-memo-btn').click(function(){
				MemoView.show();
			});

			$(CashAdvanceListControlsModel).on('change:balance', function(){
				self.txtBalance.html(this.attributes.balance);
			});

			self.listenToBtn();
		},
		listenToBtn: function(){
			var self = this;
			self.viewBtn.click(function(){
				self.el.dialog({
					title: self.title,
					modal: true,
					width: 600,
					open: function(){
						$(self.cashadvanceGrid).trigger('refresh');
					},
				});
			});
		},
	};

	return CashAdvanceListView;
});
