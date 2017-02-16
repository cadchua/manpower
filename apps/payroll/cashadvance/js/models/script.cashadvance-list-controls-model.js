/**
 * model for Cash Advance list controls
 */
define([ 'script.config' ], function(config) {
	var CashAdvanceListControls = {
		attributes : {
			employee_id : null,
			cashadvance_type : null,
			balance : 0,
			from : "",
			to : "",
		},
		updateBalance : function() {
			var self = this;
			return $.post(config.cashAdvanceAPI, {
				cmd : 'get-balance',
				employee_id : self.attributes.employee_id,
				cashadvance_type : self.attributes.cashadvance_type
			}).done(function(data){
				var data = JSON.parse(data);
				self.attributes.balance = data.balance;
				$(self).trigger('change:balance');
			});
		},
		set : function(options) {
			$.extend(this.attributes, options);
			$(this).trigger('change');
		},
	};

	return CashAdvanceListControls;
});