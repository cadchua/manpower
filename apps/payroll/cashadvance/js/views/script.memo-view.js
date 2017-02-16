/**
 * view for memo popup 
 */
define(['script.config', '../models/script.memo-model'], function(config, MemoModel){
	console.log(MemoModel);
	var MemoView = {
		id: '#drcrmemo-popup',
		title: 'Add DR/CR Memo',
		init: function(){
			var self = this;
			self.el = $(this.id);
			
			$('#memo-date').datepicker({
				dateFormat: 'yy-mm-dd',
			});
			
			$('#memo-type, #memo-date, #memo-amount').change(function(){
				MemoModel.set({
					type: $('#memo-type').val(),
					date: $('#memo-date').val(),
					amount: $('#memo-amount').val(),
				});
			});
			
			$('#memo-save-btn').click(function(){
				MemoModel.save();
			});
			
			return self;
		},
		show: function(){
			var self = this;
			
			self.el.dialog({
				title: self.title,
				modal: true,
			});
		},
	};
	
	return MemoView.init();
});