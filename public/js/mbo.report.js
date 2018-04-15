'use strict';

var mbo_report = {
  w: 3, // weight
  a: 5, // acheive
  s: 6, // score
  hw: 2, //header weight
  hs: 3, // header score
  fa: 3, // final achieve
  fs: 4, // final score
  ft: 5, // final total
}; 
document.addEventListener('DOMContentLoaded', function() {
    mbo_report.table = document.querySelector('table.mbo');
    mbo_report.form = document.querySelector('form[name="objective_report"]');

    mbo_report.form.addEventListener('change', function(event) {
      if (event.target.tagName != 'INPUT') {
        return;
      }
      var row = event.target.parentNode.parentNode;
      mbo_report.update(row);
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
                var weight = partition.rows[row_iter].cells[this.w].children[0].value;
                weight = weight.length == 0 ? 0 : parseFloat(weight);
                weight_sum += weight;
                var achieve = partition.rows[row_iter].cells[this.a].children[0].value;
                achieve = achieve.length == 0 ? 0 : parseFloat(achieve);
                var score = weight * achieve;
                partition.rows[row_iter].cells[this.s].textContent = score.toFixed(2);
                score_sum += score;
            }
            partition.rows[0].cells[this.hw].textContent = weight_sum.toFixed(2);
            partition.rows[0].cells[this.hs].textContent = score_sum.toFixed(2);
            total_score += score_sum;
        }
        else {
          var achieve = partition.rows[0].cells[this.fa].children[0].value;
          achieve = achieve.length == 0 ? 0 : parseFloat(achieve);
          var score = achieve * 0.1;
          partition.rows[0].cells[this.fs].textContent = score.toFixed(2);
          total_score += score;
          partition.rows[0].cells[this.ft].textContent = total_score.toFixed(2);
        }
    }
};

mbo_report.update = function(row) {
  var partition = row.parentNode;

  var weight = row.cells[this.w].children[0].value;
  weight = weight.length == 0 ? 0 : parseFloat(weight);
  var achieve = row.cells[this.a].children[0].value;
  achieve = achieve.length == 0 ? 0 : parseFloat(achieve);

  this.update_weight_sum(partition);

  var score = weight * achieve;
  
  var score_diff = row.cells[this.s].textContent;
  row.cells[6].textContent = score.toFixed(2);
  score_diff = score_diff.length == 0 ? 0 : parseFloat(score_diff);
  score_diff = score - score_diff;

  this.update_total_score(partition, score_diff);
};

mbo_report.update_total_score = function(partition, score_diff) {
  var score_sum = partition.rows[0].cells[this.hs].textContent;
  score_sum = score_sum.length == 0 ? 0 : parseFloat(score_sum);
  score_sum += score_diff;
  partition.rows[0].cells[this.hs].textContent = score_sum.toFixed(2);
}

mbo_report.update_weight_sum = function (partition) {
  if (!partition.classList.contains('partition')) {
    return;
  }

  var weight_sum = 0;
  for (var row_iter = 1; row_iter < partition.rows.length; row_iter++) {
      var weight = partition.rows[row_iter].cells[3].children[0].value;
      weight = weight.length == 0 ? 0 : parseFloat(weight);
      weight_sum += weight;
  }
  partition.rows[0].cells[2].textContent = weight_sum.toFixed(2);
};

