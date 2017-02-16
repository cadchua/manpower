/**
 * module for employee info
 */
define(['script.config', 'lodash', '../models/script.employee-info-model'], function(config, _, EmployeeInfo){
	var EmployeeInfoView = function(options){
		var self = this;
		$.extend(this, options);
		self.el = $(self.id);
		self.infoFields = {
				id: self.el.find('.employee-id'),
				name: self.el.find('.employee-name'),
				photo: self.el.find('.employee-img'),
		}
		
		function listenToInfo(){
			$(EmployeeInfo).on('change', function(){
				self.infoFields.id.html(this.id);
				self.infoFields.name.html(this.name);
				self.infoFields.photo.attr('src', "data:image/jpeg;base64,"+ this.photo);
			});
		}
		
		listenToInfo();
	};
	return EmployeeInfoView;
});