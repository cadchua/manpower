/**
 * main module for sss
 */
requirejs.config({
	baseUrl: 'js/',
	paths: {
		lodash: '../../../../js/lodash.min'
	}
});
require(['script.config', 'script.payroll-config-form', 'script.payroll-config-grid'], function(config, PayrollConfigForm, PayrollConfigGrid){
	PayrollConfigGrid.init();
	PayrollConfigForm.init(PayrollConfigGrid);
});