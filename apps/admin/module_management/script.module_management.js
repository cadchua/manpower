/**
 * main script for module management
 */
requirejs.config({
	baseUrl: 'js/',
});

require(['script.config', 'script.module-grid', 'script.category-grid'], function(config, ModuleGrid, CategoryGrid){
	CategoryGrid.init();
	ModuleGrid.init(CategoryGrid);
});