/**
 * grid for sss 
 */
define(['script.config'], function(config){
	var SSSGrid = {
			id: '#sss-grid',
			name: 'SSSGrid',
			init: function(){
				var self = this;
				
				self.el = $(self.id);
				self.loadGrid();
			},
			loadGrid: function(){
				var self = this;
				
				self.grid = self.el.w2grid({
					name: self.name,
					url: config.sssApiUrl,
					header: "Contributions",
					show: {
						header: true,
					},
					columns: [
					   {field: 'bracket_no', caption: 'Bracket', size: '20%'},
					   {field: 'salary', caption: 'Salary', size: '20%', render: 'number:2'},
					   {field: 'monthly_credit', caption: 'Monthly Credit', size: '20%', render: 'number:2'},
					   {field: 'employer_share', caption: 'Employer Share', size: '20%', render: 'number:2'},
					   {field: 'employee_share', caption: 'Employee Share', size: '20%', render: 'number:2'},
					   {field: 'total_contribution', caption: 'Total Contribution', size: '20%', render: 'number:2'},
					]
				});
			},
	}
	
	return SSSGrid;
});