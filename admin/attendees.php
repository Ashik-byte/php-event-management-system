<?php 
    include 'session-check.php';
    include "header.php";
    include "navbar.php"; 
?>

<div class="wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="content-area" id="contentArea">
        <div class="modal fade" id="modal_add_attendee" tabindex="-1" role="dialog" aria-labelledby="modal_add_attendee_label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Attendee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="add_attendee_form" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Attendee Name</label>
                                        <input class="form-control" id="add_attendee_name" name="add_attendee_name" type="text" autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Select Event</label>
                                        <select id="add_attendee_event_id" name="event_id" class="form-control" required>
                                            <option value="">--select--</option>
                                            <?php
                                                $query = "SELECT id, name FROM events ORDER BY name ASC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->execute();

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row["id"] . '">' . htmlspecialchars($row["name"]) . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group mb-3">
                                        <label>Attendee Email</label>
                                        <input class="form-control" id="add_attendee_email" name="add_attendee_email" type="email" autocomplete="off" required />
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_edit_attendee" tabindex="-1" role="dialog" aria-labelledby="modal_edit_attendee_label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Attendee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit_attendee_form" method="POST">
                            <input type="hidden" id="edit_attendee_id" name="edit_attendee_id" />
                            <input type="hidden" id="edit_attendee_prev_event_id" name="edit_prev_event_id" />             
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Attendee Name</label>
                                        <input class="form-control" id="edit_attendee_name" name="edit_attendee_name" type="text" autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Select Event</label>
                                        <select id="edit_attendee_event_id" name="edit_event_id" class="form-control" required>
                                            <option value="">--select--</option>
                                            <?php
                                                $query = "SELECT id, name FROM events ORDER BY name ASC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->execute();

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row["id"] . '">' . htmlspecialchars($row["name"]) . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group mb-3">
                                        <label>Attendee Email</label>
                                        <input class="form-control" id="edit_attendee_email" name="edit_attendee_email" type="email" autocomplete="off" required />
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                    <div class="breadcrumb-holder">
                            <h1 class="main-title float-left">Attendees</h1>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active">Attendees</li>
                            </ol>
                            <div class="clearfix"></div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">						
                <div class="card mb-3">
                    <div class="card-header">
                        <span id = "add_attendee" class="pull-right"><button class="btn btn-primary btn-md" data-toggle="modal" data-target="#modal_add_attendee"><i class="fa fa-plus" style="margin-right: 4px;" aria-hidden="true"></i>Add New Attendee</button></span>
                        <h3><i class="fa fa-list"></i> All Attendees</h3>		

                    </div>                 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="order_data" class="table table-bordered table-hover display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Event</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>					
                    </div>
                </div><!-- end card-->					
            </div>	
        </div>
    </div>
</div>

<?php 
    include "footer.php"; 
?>

</body>
</html>
<script>
$(document).ready(function(){

    /* For Export Buttons available inside jquery-datatable "server side processing" - Start
    - due to "server side processing" jquery datatble doesn't support all data to be exported
    - below function makes the datatable to export all records when "server side processing" is on */
    function newexportaction(e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
        // Call the original action function
        if (button[0].className.indexOf('buttons-copy') >= 0) {
        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
        } else if (button[0].className.indexOf('buttons-excel') >= 0) {
        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
        } else if (button[0].className.indexOf('buttons-csv') >= 0) {
        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
        } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
        } else if (button[0].className.indexOf('buttons-print') >= 0) {
        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
        }
        dt.one('preXhr', function (e, s, data) {
        // DataTables thinks the first item displayed is index 0, but we're not drawing that.
        // Set the property to what it was before exporting.
        settings._iDisplayStart = oldStart;
        data.start = oldStart;
        });
        // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
        setTimeout(dt.ajax.reload, 0);
        // Prevent rendering of the full data to the DOM
        return false;
        });
        });
        // Requery the server with the new one-time export settings
        dt.ajax.reload();
    };
    //For Export Buttons available inside jquery-datatable "server side processing" - End

    fetch_data('no');

    function fetch_data(){

        let filename = moment().format('YYYY-MM-DD');

        var dataTable = $('#order_data').DataTable({
            dom: 'Bfrtip', // Corrected typo: `1Bfrtip` → `Bfrtip`
            processing: true,
            serverSide: true,
            scrollX: false,
            responsive: true,
            autoWidth: true, // Prevent incorrect width issues
            pagingType: "numbers",
            ajax: {
                url: "../core/admin/fetch-attendees.php",
                type: "POST"
            },
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, "asc"]],
            columnDefs: [{
                targets: [4],
                orderable: false
            }],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: null,
                    action: newexportaction,
                    filename: 'Attendee_List_' + new Date().toISOString().slice(0, 10), // Fix filename format
                    exportOptions: { columns: [0, 1, 2, 3] }
                }
            ],
            language: {
                infoFiltered: ""
            },
            info: false,
            fnDrawCallback: function() {
                if ($('#order_data td').hasClass('sRowEmpty')) {
                    $('div.dataTables_paginate.paging_full_numbers').hide();
                } else {
                    $('div.dataTables_paginate.paging_full_numbers').show();
                }
            }
        });
    }
});
</script>