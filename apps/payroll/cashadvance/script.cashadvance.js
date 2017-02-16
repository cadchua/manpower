/**
 * main module for cash advance 
 */
requirejs.config({
	baseUrl: 'js/',
	shim: {
		lodash: {
			exports: '_'
		},
	},
	paths: {
		lodash: '../../../../js/lodash.min',
	}
});

require(['script.config', 
         'script.employee-grid', 
         'views/script.employee-info-view', 
         'views/script.cashadvance-list-view',
         'script.cashadvance-accounts',
         'script.tabs'], function(config, EmployeeGrid, EmployeeInfoView, CashAdvanceListPopup, CashadvanceAccounts, Tabs){
	Tabs.init();
	EmployeeGrid.init();
	var mainEmployeeInfoView = new EmployeeInfoView({
		id: '#employee-info-container',
	});
	CashadvanceAccounts.init();
	CashAdvanceListPopup.initialize(CashadvanceAccounts);
	
});