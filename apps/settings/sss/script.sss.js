/**
 * main module for sss
 */
requirejs.config({
	baseUrl: 'js/',
	paths: {
		lodash: '../../../../js/lodash.min'
	}
});
require(['script.config', 'script.sss-form', 'script.sss-grid'], function(config, SSSForm, SSSGrid){
	SSSGrid.init();
	SSSForm.init(SSSGrid);
});