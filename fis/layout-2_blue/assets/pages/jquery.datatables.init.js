/**
* Theme: Flacto Admin Template
* Author: Coderthemes
* Component: Datatable
* 
*/

var handleDataTableButtons = function () {
    "use strict";
    0 !== $("#datatable").length && $("#datatable").DataTable({
        ajax: "./layout-2_blue/assets/plugins/datatables/json/scroller-demo.json",
        dom: "Bfrtip",
        buttons: [{extend: "copy", className: "btn-sm"}, {extend: "csv", className: "btn-sm"}, {
            extend: "excel",
            className: "btn-sm"
        }, {extend: "pdf", className: "btn-sm"}, {extend: "print", className: "btn-sm"}],
        responsive: !0
    })
}, TableManageButtons = function () {
    "use strict";
    return {
        init: function () {
            handleDataTableButtons()
        }
    }
}();