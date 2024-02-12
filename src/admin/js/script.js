// Data table

jQuery(document).ready(function() {
    
    let table = jQuery('#all-subscriber-mep-table').DataTable({
        select: {
            style: 'multi',
            selector: 'th:first-child input:checkbox',
            selectAll: true
        }
    });
});

