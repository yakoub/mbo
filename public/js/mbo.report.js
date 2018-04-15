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
    var total_score = 0;
    for (var iter = 0; iter < this.partitions.length; iter++) {
        var partition = this.partitions[iter];
        if (partition.classList.contains('partition')) {
            var weight_sum = 0;
            var score_sum = 0;
            for (var row_iter = 1; row_iter < partition.rows.length; row_iter++) {
                var weight = partition.rows[row_iter].cells[3].children[0].value;
                weight = weight.length == 0 ? 0 : parseFloat(weight);
                weight_sum += weight;
                var achieve = partition.rows[row_iter].cells[5].children[0].value;
                achieve = achieve.length == 0 ? 0 : parseFloat(achieve);
                var score = weight * achieve;
                partition.rows[row_iter].cells[6].textContent = score.toFixed(2);
                score_sum += score;
            }
            partition.rows[0].cells[2].textContent = weight_sum.toFixed(2);
            partition.rows[0].cells[3].textContent = score_sum.toFixed(2);
            total_score += score_sum;
        }
        else {
          var achieve = partition.rows[0].cells[3].children[0].value;
          achieve = achieve.length == 0 ? 0 : parseFloat(achieve);
          var score = achieve * 0.1;
          partition.rows[0].cells[4].textContent = score.toFixed(2);
          total_score += score;
          partition.rows[0].cells[5].textContent = total_score.toFixed(2);
        }
    }
};
