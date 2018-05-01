
function activate_children() {
    var id = this.dataset.id;
    var req = new XMLHttpRequest();
    req.addEventListener('load', function() {
        if (this.status == 204) {
            window.alert('empty children set');
            return;
        }
        if (this.status != 200) {
            window.alert('request failed, please reload page');
            return;
        }
        var id = JSON.parse(this.responseText);
        window.alert('request succeeded');
        var children_selector = 'table.children-for-' + id + ' td.status span.badge';
        var children_cells = document.querySelectorAll(children_selector);
        children_cells.forEach(function(cell_badge) {
            cell_badge.classList.remove('badge-secondary');
            cell_badge.classList.add('badge-success');
            cell_badge.textContent = 'Active';
        });
    });
    req.open('POST', '/activate-children/' + id);
    req.send();
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('button.activate-children').forEach(function(btn){
        btn.onclick = activate_children;
    });
});
