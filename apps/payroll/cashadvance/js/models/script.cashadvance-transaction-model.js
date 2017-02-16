/**
 * model for currently selected transaction in cashadvance accounts grid
 */
define(['script.config'], function(config){
	var CATransactionModel = {
		attributes: {
			cashadvance_account: null,
			tr_date: new Date(),
			debit: 0,
			credit: 0,
		},
		set: function(options){
			$.extend(this.attributes, options);
			$(this).trigger('change', [Object.keys(options)]);
		},
		saveHold: function(){
			var self = this;
			return $.post(config.cashAdvanceAPI, {cmd: 'update-hold', cashadvance_account: this.attributes.cashadvance_account, hold: this.attributes.hold });
		},
	};
	return CATransactionModel;
});