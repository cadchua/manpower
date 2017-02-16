/**
 * model for memo popup
 */
define([ 'script.config', './script.cashadvance-list-controls-model' ], function(config, CashAdvanceListControls) {
	var MemoModel = {
		attributes : {
			type : null,
			date : null,
			amount : 0,
		},
		set : function(options) {
			$.extend(this.attributes, options);
			$(this).trigger('change');
		},
		save : function(account) {
			console.log(CashAdvanceListControls.attributes);
			return $.post(config.cashAdvanceAPI, {
				cmd : 'add-memo',
				cashadvance_account : CashAdvanceListControls.attributes,
				transaction : this.attributes
			});
		}
	};
	return MemoModel;
});