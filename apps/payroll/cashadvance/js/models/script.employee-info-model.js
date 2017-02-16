/**
 * model that keeps track of the employee's info 
 */
define(['script.config'], function(config){
	var EmployeeInfoModel = {
		id: null,
		name: "",
		photo: null,
		setId: function(id){
			var self = this;
			var employeeInfo = $.post(config.employeeAPI, {cmd: 'getinfo', employee_id: id});
			var employeePhoto = $.post(config.employeeAPI, {cmd: 'loadphoto', id: id});
			
			$.when(employeeInfo, employeePhoto)
				.done(function(info, photo){
					var info = JSON.parse(info[0]);
					var photo = JSON.parse(photo[0]);
					self.id = info.info.employee_id;
					self.name = info.info.name;
					self.photo = photo.img;
					$(self).trigger('change');
			});
		}
	};
	
	return EmployeeInfoModel;
});