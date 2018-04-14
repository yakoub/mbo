'use strict';

var mbo_report = {}; 
document.addEventListener('DOMContentLoaded', function() {
    mbo_report.table = document.querySelector('table.mbo');
    mbo_report.form = document.querySelector('form[name="objective_report"]');

    mbo_report.form.addEventListener('change', function(event) {
        console.log('change');
    });

    mbo_report.scan();
});

mbo_report.scan = function() {
    this.partitions = this.table.tBodies;
    for (var iter = 0; iter < this.partitions.length; iter++) {
        var partition = this.partitions[iter];
        if (partition.classList.contains('partition')) {
            var weight_sum = 0;
            var achieve_sum = 0;
            for (var row_iter = 1; row_iter < partition.rows.length; row_iter++) {
                var weight = partition.rows[row_iter].cells[3].children[0].value;
                weight_sum += parseFloat(weight);
                var achieve = partition.rows[row_iter].cells[5].children[0].value;
                achieve_sum += parseFloat(achieve);
            }
            partition.rows[0].cells[2].textContent = weight_sum;
            partition.rows[0].cells[3].textContent = achieve_sum;
        }
    }
};
