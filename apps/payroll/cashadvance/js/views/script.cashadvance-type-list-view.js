/**
 * view for cashadvance type list dropdown
 */
define(['script.config',],
        function(config){
	var CashAdvanceTypeListView = function(options){
		var self = this;

		$.extend(this, options);
		self.el = $(this.id);

		self.cashAdvanceTypeList.loadTypes.done(function(types){
			var option = $('<option>');

			self.el.length = 1;

			types.forEach(function(type){
				option.clone()
					.val(type.payconfig_id)
					.html(type.pay_name)
					.appendTo(self.el);
			});

			self.el.on('change', function(){
				$(self).trigger('change', [self.el.val()]);
			});
		});
		return self;
	};

	return CashAdvanceTypeListView;
});
