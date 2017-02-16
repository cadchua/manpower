requirejs.config({
	baseUrl: 'js/',
	paths: {
		lodash: '../../../../js/lodash.min'
	}
});

require(['script.config', 'lodash', 'script.workgroup-grid', 'script.module-grid'], function(config, _, WorkgroupGrid, ModuleGrid){
	WorkgroupGrid.init();
	ModuleGrid.init(WorkgroupGrid);
});