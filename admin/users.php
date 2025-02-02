<?php 
    include 'session-check.php';
    if ($_SESSION['is_admin'] !== 1){
        die();
    }
    include "header.php";
    include "navbar.php"; 
?>

<div class="wrapper">
    <?php include 'sidebar.php'; ?>

    <div class="content-area" id="contentArea">
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_add_user">
            <div class="modal-dialog">
                <div class="modal-content">  
                    <form id="user_add_form" data-parsley-validate method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Add User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">                                                 
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>User Name</label>
                                        <input class="form-control" id="user_name" name="user_name" type="text" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>User Email</label>
                                        <input class="form-control" id="user_email" name="user_email" type="email" autocomplete="off" required/>
                                    </div>
                                </div>  
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Password</label>
                                        <input class="form-control" id="user_pass" name="user_pass" type="password" autocomplete="off" required/>
                                    </div>
                                </div>  

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Type</label>
                                        <select id="user_type" name="user_type" class="form-control" required>
                                            <option value="">--select--</option>
                                            <option value="1">admin</option>
                                            <option value="0">user</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Active</label>
                                        <select id="user_active" name="user_active" class="form-control" required>
                                            <option value="">--select--</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                        </div>             
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning">Save</button>
                        </div>    
                    </form>                
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="user_edit_modal">
            <div class="modal-dialog">
                <div class="modal-content">  
                    <form id="user_edit_form" data-parsley-validate method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">     
                            <div class="row">
                                <input class="form-control" id="edit_user_id" name="user_id" type="hidden" autocomplete="off" required/>
                            </div>                                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>User Name</label>
                                        <input class="form-control" id="edit_user_name" name="user_name" type="text" autocomplete="off" required/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>User Email</label>
                                        <input class="form-control" id="edit_user_email" name="user_email" type="email" autocomplete="off" required/>
                                    </div>
                                </div>  
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label>Password</label>
                                        <input class="form-control" id="edit_user_pass" name="user_pass" type="password" autocomplete="off"/>
                                        <small class="text-muted" style="font-size: 0.75rem;">(Leave blank to keep current password)</small>
                                    </div>
                                </div>  

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Type</label>
                                        <select id="edit_user_type" name="user_type" class="form-control" required>
                                            <option value="">--select--</option>
                                            <option value="1">admin</option>
                                            <option value="0">user</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label>Active</label>
                                        <select id="edit_user_active" name="user_active" class="form-control" required>
                                            <option value="">--select--</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
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
                            <h1 class="main-title float-left">Users</h1>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                            <div class="clearfix"></div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">						
                <div class="card mb-3">
                    <div class="card-header">
                        <span id = "add_user" class="pull-right"><button class="btn btn-primary btn-md" data-toggle="modal" data-target="#modal_add_user"><i class="fa fa-plus" style="margin-right: 4px;" aria-hidden="true"></i>Add New User</button></span>
                        <h3><i class="fa fa-list"></i> All Users</h3>		

                    </div>                 
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="order_data" class="table table-bordered table-hover display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Image</th>
                                        <th>Type</th>
                                        <th>Active</th>
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
            autoWidth: false, // Prevent incorrect width issues
            pagingType: "numbers",
            order: [],
            ajax: {
                url: "../core/admin/fetch-users.php",
                type: "POST"
            },
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[0, "asc"]],
            columnDefs: [{
                targets: [3, 5, 6],
                orderable: false
            }],
            buttons: [
                {
                    extend: 'excelHtml5',
                    title: null,
                    action: newexportaction,
                    filename: 'User_List_' + new Date().toISOString().slice(0, 10),
                    exportOptions: { columns: [0, 1, 2, 4] }
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