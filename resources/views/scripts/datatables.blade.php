<script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/dataTables.material.min.js"></script>

<script type="text/javascript">

    (function(window, document, undefined) {
      var factory = function($, DataTable) {
        "use strict";
  
        /* Set the defaults for DataTables initialisation */
        $.extend(true, DataTable.defaults, {
          dom: "<'hiddensearch'f'>" +
            "tr" +
            "<'table-footer'lip'>",
          renderer: 'material'
        });
  
        /* Default class modification */
        $.extend(DataTable.ext.classes, {
          sWrapper: "dataTables_wrapper",
          sFilterInput: "form-control input-sm",
          sLengthSelect: "form-control input-sm"
        });
  
      }; // /factory
  
      // Define as an AMD module if possible
      if (typeof define === 'function' && define.amd) {
        define(['jquery', 'datatables'], factory);
      } else if (typeof exports === 'object') {
        // Node/CommonJS
        factory(require('jquery'), require('datatables'));
      } else if (jQuery) {
        // Otherwise simply initialise as normal, stopping multiple evaluation
        factory(jQuery, jQuery.fn.dataTable);
      }
  
    })(window, document);
  
    $(document).ready(function() {
      $('.data-table').dataTable({
        "aaSorting": [],
       'aoColumnDefs': [{
          'bSortable': false,
          'searchable': false,
          'aTargets': ['no-search'],
          'bTargets': ['no-sort']
        }],
        "bLengthChange": false,
        "bInfo": false,
        "pagingType": "full_numbers",
        "oLanguage": {
          "sStripClasses": "",
          "sSearch": "",
          "sSearchPlaceholder": "Enter Keywords Here",
        },
        bAutoWidth: false
      });
    });
      $(document).on('input', "#search_table", '.mdl-textfield__input', function(){
        var oTable = $('.dataTable').dataTable();
        oTable.fnFilter($(this).val());
      });
  
  </script>