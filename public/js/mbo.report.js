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
                var row = partition.rows[row_iter];
                var weight = this.getPartitionWeight(row);
                weight_sum += weight;
                var achieve = this.getPartitionAchieve(row);
                var score = weight * (achieve/100);

                this.setPartitionScore(row, score);
                score_sum += score;
            }
            this.setPartitionTotalWeight(partition, weight_sum);
            this.setPartitionTotalScore(partition, score_sum);
            total_score += score_sum;
            table_total += (score_sum ? score_sum : weight_sum);
        }
        else {

          var achieve = this.getReviewerAchieve(partition);
          var score = (achieve/100) * 10;
          this.setReviewerScore(partition, score);
          total_score += score;
          this.setReviewerTotal(partition, total_score);
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
    this.update_reviewer(partition);
  }
};

mbo_report.update_reviewer = function(partition) {
  var achieve = this.getReviewerAchieve(partition);
  
  var score_diff = this.getReviewerScore(partition);
  var score = (achieve/100) * 10;
  this.setReviewerScore(partition, score);

  score_diff = score - score_diff;
  var score_total = this.getReviewerTotal(partition);
  score_total += score_diff;
  this.setReviewerTotal(partition, score_total);

  var table_total = score_total;

  if (partition.classList.contains('vp')) {
    var vp = partition.parentNode.querySelector('tbody.ceo');
    score_total = this.getReviewerTotal(vp);
    score_total += score_diff;
    this.setReviewerTotal(vp, score_total);
    table_total = score_total;
  }
  document.querySelector('var.table-total').textContent = table_total.toFixed(2);
};

mbo_report.update_partition = function(row, partition) {
  var weight = this.getPartitionWeight(row);
  var achieve = this.getPartitionAchieve(row);

  var weight_diff = this.update_weight_sum(partition);

  var score = weight * (achieve/100);
  
  var score_diff = this.getPartitionScore(row);
  score_diff = score - score_diff;
  this.setPartitionScore(row, score);
  var score_sum = this.update_total_score(partition, score_diff);

  if (score_diff != 0 && score_sum != 0) {
    this.update_total_table(score_diff);
    return;
  }
  if (weight_diff != 0 && score == 0) {
    this.update_total_table(weight_diff);
  }
};

mbo_report.update_total_score = function(partition, score_diff) {
  var score_sum = this.getPartitionTotalScore(partition);
  score_sum += score_diff;
  this.setPartitionTotalScore(partition, score_sum);

  var partitions = partition.parentNode.tBodies;
  [3, 4].forEach(function(iter) {
    var score_total = this.getReviewerTotal(partitions[iter]);
    score_total += score_diff;
    this.setReviewerTotal(partitions[iter], score_total);
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
      var row = partition.rows[row_iter];
      var weight = this.getPartitionWeight(row);
      weight_sum += weight;
  }
  var weight_diff = this.getPartitionTotalWeight(partition);
  this.setPartitionTotalWeight(partition, weight_sum);
  weight_diff = weight_sum - weight_diff;
  return weight_diff;
};

mbo_report.getPartitionWeight = function(row) {
  var weight = row.cells[this.w].children[0].value;
  return weight.length == 0 ? 0 : parseFloat(weight);
};

mbo_report.getPartitionAchieve = function(row) {
  var achieve = row.cells[this.a].children[0].value;
  return achieve.length == 0 ? 0 : parseFloat(achieve);
};

mbo_report.getPartitionScore = function(row) {
  var score = row.cells[this.s].textContent;
  return score.length == 0 ? 0 : parseFloat(score);
};

mbo_report.setPartitionScore = function(row, score) {
  row.cells[this.s].textContent = score.toFixed(2);
};

mbo_report.setPartitionTotalWeight = function(partition, weight) {
  partition.rows[0].cells[this.hw].textContent = weight.toFixed(2);
};

mbo_report.setPartitionTotalScore = function(partition, score) {
  partition.rows[0].cells[this.hs].textContent = score.toFixed(2);
};

mbo_report.getPartitionTotalWeight = function(partition) {
  var weight = partition.rows[0].cells[this.hw].textContent;
  return weight.length == 0 ? 0 : parseFloat(weight);
};

mbo_report.getPartitionTotalScore = function(partition) {
  var score = partition.rows[0].cells[this.hs].textContent;
  return score.length == 0 ? 0 : parseFloat(score);
};

mbo_report.getReviewerAchieve = function(partition) {
  var achieve = partition.rows[0].cells[this.fa].children[0].value;
  return achieve.length == 0 ? 0 : parseFloat(achieve);
};
mbo_report.setReviewerScore = function(partition, score) {
  partition.rows[0].cells[this.fs].textContent = score.toFixed(2);
};
mbo_report.setReviewerTotal = function(partition, score) {
  partition.rows[0].cells[this.ft].textContent = score.toFixed(2);
};
mbo_report.getReviewerScore = function(partition) {
  var score = partition.rows[0].cells[this.fs].textContent;
  return score.length == 0 ? 0 : parseFloat(score);
};
mbo_report.getReviewerTotal = function(partition) {
  var score = partition.rows[0].cells[this.ft].textContent;
  return score.length == 0 ? 0 : parseFloat(score);
};
