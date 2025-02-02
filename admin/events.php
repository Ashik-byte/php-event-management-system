<?php 
    include 'session-check.php';
    if ($_SESSION['is_admin'] !== 1 && $_SESSION['is_admin'] !== 0){
        die();
    }
    include "header.php";
    include "navbar.php"; 
?>
<script>
    var isAdmin = <?php echo $_SESSION["is_admin"]; ?>;
</script>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="content-area" id="contentArea">
        <div class="modal fade" id="event_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">  
                    <form id="add_event_form" data-parsley-validate method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">     
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Event Name</label>
                                        <input class="form-control" id="add_event_name" name="event_name" type="text" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Event Date & Time</label>
                                        <input type="text" id="add_event_date" name="event_date" class="form-control event_date_time" required/>
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label>Event Location</label>
                                        <input class="form-control" id="add_event_location" name="event_location" type="text" autocomplete="off" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Capacity</label>
                                        <input class="form-control" id="add_event_capacity" name="event_capacity" type="number" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Attendees</label>
                                        <input class="form-control" id="add_event_attendees" name="event_attendees" type="number" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Event Image Upload</label>
                                        <input class="form-control" id="add_event_image" name="event_image" type="file" accept="image/*"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label>Description</label>
                                        <textarea id="add_event_description" name="event_description" class="form-control" rows="4" autocomplete="off" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>             
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add Event</button>
                        </div>    
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="event_edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">  
                    <form id="edit_event_form" data-parsley-validate method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">     
                            <input type="hidden" id="edit_event_id" name="event_id" />
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Event Name</label>
                                        <input class="form-control" id="edit_event_name" name="event_name" type="text" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Event Date & Time</label>
                                        <input type="text" id="edit_event_date" name="event_date" class="form-control event_date_time" />
                                    </div>
                                </div>  
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label>Event Location</label>
                                        <input class="form-control" id="edit_event_location" name="event_location" type="text" autocomplete="off" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Capacity</label>
                                        <input class="form-control" id="edit_event_capacity" name="event_capacity" type="number" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Attendees</label>
                                        <input class="form-control" id="edit_event_attendees" name="event_attendees" type="number" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Event Image Upload</label>
                                        <input class="form-control" id="edit_event_image" name="event_image" type="file" accept="image/*"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label>Description</label>
                                        <textarea id="edit_event_description" name="event_description" class="form-control" rows="4" autocomplete="off" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>             
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning">Save Changes</button>
                        </div>    
                    </form>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xl-12">
                    <div class="breadcrumb-holder">
                            <h1 class="main-title float-left">Events</h1>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active">Events</li>
                            </ol>
                            <div class="clearfix"></div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">						
                <div class="card mb-3">
                    <div class="card-header">
                        <span id = "add_event" class="pull-right"><button class="btn btn-primary btn-md" data-toggle="modal" data-target="#modal_add_event"><i class="fa fa-plus" style="margin-right: 4px;" aria-hidden="true"></i>Add New Event</button></span>
                        <h3><i class="fa fa-list"></i> All Events</h3>		

                    </div>                 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="order_data" class="table table-bordered table-hover display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Date Time</th>
                                        <th>Capacity</th>
                                        <th>Attendees Count</th>
                                        <th>Image</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                        <?php if ($_SESSION["is_admin"] == 1) { ?>
                                            <th>Download Attendee List</th>
                                        <?php } ?>
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

        let columnsArray = [
            { data: "id" },
            { data: "name" },
            { data: "description" },
            { data: "date_time" },
            { data: "capacity" },
            { data: "attendees_count" },
            { data: "image" },
            { data: "address" },
            { data: "action", orderable: false }
        ];

        let columnDefsArray = [
            { targets: [2, 6, 7, 8], orderable: false } // Common non-sortable columns
        ];

        if (isAdmin) {
            columnsArray.push({ data: "download_attendee_list", orderable: false });
            columnDefsArray[0].targets.push(9);
        }

        var dataTable = $('#order_data').DataTable({
            dom: 'Bfrtip',
            processing: true,
            serverSide: true,
            scrollX: false,
            responsive: true,
            autoWidth: false, 
            pagingType: "numbers",
            ajax: {
                url: "../core/admin/fetch-events.php",
                type: "POST"
            },
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, "asc"]],
            columnDefs:columnDefsArray,
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: null,
                    action: newexportaction,
                    filename: 'Event_List_' + new Date().toISOString().slice(0, 10),
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 7] }
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