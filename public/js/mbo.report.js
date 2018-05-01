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
    var table_total = 0;
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
                var score = weight * (achieve/100);
                partition.rows[row_iter].cells[this.s].textContent = score.toFixed(2);
                score_sum += score;
            }
            partition.rows[0].cells[this.hw].textContent = weight_sum.toFixed(2);
            partition.rows[0].cells[this.hs].textContent = score_sum.toFixed(2);
            total_score += score_sum;
            table_total += (score_sum ? score_sum : weight_sum);
        }
        else {
          var achieve = partition.rows[0].cells[this.fa].children[0].value;
          achieve = achieve.length == 0 ? 0 : parseFloat(achieve);
          var score = (achieve/100) * 10;
          partition.rows[0].cells[this.fs].textContent = score.toFixed(2);
          total_score += score;
          partition.rows[0].cells[this.ft].textContent = total_score.toFixed(2);
          table_total += score;
        }
    }
    document.querySelector('var.table-total').textContent = table_total.toFixed(2);
};

mbo_report.update = function(row) {
  var partition = row.parentNode;
  if (partition.classList.contains('partition')) {
    this.update_partition(row, partition);
  }
  else {
    this.update_final(row, partition);
  }
};

mbo_report.update_final = function(row, partition) {
  var achieve = row.cells[this.fa].children[0].value;
  achieve = achieve.length == 0 ? 0 : parseFloat(achieve);
  
  var score_diff = row.cells[this.fs].textContent;
  score_diff = score_diff.length == 0 ? 0 : parseFloat(score_diff);
  var score = (achieve/100) * 10;
  row.cells[this.fs].textContent = score.toFixed(2);

  score_diff = score - score_diff;
  var score_total = row.cells[this.ft].textContent;
  score_total = score_total.length == 0 ? 0 : parseFloat(score_total);
  score_total += score_diff;
  row.cells[this.ft].textContent = score_total.toFixed(2);

  var table_total = score_total;

  if (partition.classList.contains('vp')) {
    var vp = partition.parentNode.querySelector('tbody.ceo');
    score_total = vp.rows[0].cells[this.ft].textContent;
    score_total = score_total.length == 0 ? 0 : parseFloat(score_total);
    score_total += score_diff;
    vp.rows[0].cells[this.ft].textContent = score_total.toFixed(2);
    table_total = score_total;
  }
  document.querySelector('var.table-total').textContent = table_total.toFixed(2);
};

mbo_report.update_partition = function(row, partition) {
  var weight = row.cells[this.w].children[0].value;
  weight = weight.length == 0 ? 0 : parseFloat(weight);
  var achieve = row.cells[this.a].children[0].value;
  achieve = achieve.length == 0 ? 0 : parseFloat(achieve);

  var weight_diff = this.update_weight_sum(partition);

  var score = weight * (achieve/100);
  
  var score_diff = row.cells[this.s].textContent;
  row.cells[6].textContent = score.toFixed(2);
  score_diff = score_diff.length == 0 ? 0 : parseFloat(score_diff);
  score_diff = score - score_diff;
  var score_sum = this.update_total_score(partition, score_diff);

  if (score_diff != 0 && score_sum != 0) {
    this.update_total_table(_diff);
    return;
  }
  if (weight_diff != 0 && score == 0) {
    this.update_total_table(weight_diff);
  }
};

mbo_report.update_total_score = function(partition, score_diff) {
  var score_sum = partition.rows[0].cells[this.hs].textContent;
  score_sum = score_sum.length == 0 ? 0 : parseFloat(score_sum);
  score_sum += score_diff;

  var total_diff = partition.rows[0].cells[this.hs].textContent;
  total_diff = total_diff.length == 0 ? 0 : parseFloat(total_diff);
  partition.rows[0].cells[this.hs].textContent = score_sum.toFixed(2);
  total_diff = score_sum - total_diff;

  [3, 4].forEach(function(iter) {
    var score_total = partition.parentNode.tBodies[iter].rows[0].cells[this.ft].textContent;
    score_total = score_total.length == 0 ? 0 : parseFloat(score_total);
    score_total += total_diff;
    partition.parentNode.tBodies[iter].rows[0].cells[this.ft].textContent = score_total.toFixed(2);
  }, this);

  return score_sum;
};

mbo_report.update_total_table = function (total_diff) {
  var table_total = document.querySelector('var.table-total').textContent;
  table_total = table_total.length == 0 ? 0 : parseFloat(table_total);
  table_total += total_diff;
  document.querySelector('var.table-total').textContent = table_total.toFixed(2);
};

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
  var weight_diff = partition.rows[0].cells[2].textContent;
  partition.rows[0].cells[2].textContent = weight_sum.toFixed(2);
  weight_diff = weight_diff.length == 0 ? 0 : parseFloat(weight_diff);
  weight_diff = weight_sum - weight_diff;
  return weight_diff;
};

