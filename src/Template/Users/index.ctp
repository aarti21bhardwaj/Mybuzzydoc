<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <?php 
                                if($loggedInUser['role']['name'] != 'staff_manager'){
                                ?>
                        <div class="text-right">
                            <?=$this->Html->link('Add New User', ['controller' => 'users', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="ibox-content">
                    <div class="alert alert-success"> 
                            <strong> You can see a list of all of your staff users and edit their roles. Staff Admins have full access and the ability to add/edit program settings. Staff Managers have limited access and can only view settings and manage patients.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th>No.</th>
                            <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Staff member’s name." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Name</th>
                            </div>
                            <th>Practice Name</th>
                            <th class="hidden-xs">Email</th>
                            <th class="hidden-xs hidden-sm">Phone</th>
                            <div class="tooltip-demo">
                                <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" Define the role each staff member will have: Staff Admin or Staff Manager." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Role</th>
                            </div>
                            <th class="actions"><?= __('Actions') ?></th>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($users as $key => $user): ?>
                                <tr>
                                    <td><?= $this->Number->format($key+1) ?></td>
                                    <td><?= h($user->full_name) ?></td>
                                    <td><?= h($user->vendor['org_name']) ?></td>
                                    <td class="hidden-xs"><?= h($user->email) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($user->phone) ?></td>
                                    <td class="hidden-xs hidden-sm"><?= h($user->role['label']) ?></td>
                                    
                                    <!-- <td class="hidden-xs hidden-sm"><?= h(($user->is_deleted)?'Yes':'No') ?></td> -->
                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $user->id]).' class="btn btn-xs btn-success">' ?>
                                        <i class="fa fa-eye fa-fw"></i>
                                    </a>
                                    <?= '<a href='.$this->Url->build(['action' => 'edit', $user->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?php
                                if($loggedInUser['id'] != $user->id){ 
                                    echo  $this->Form->postLink(__(''), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]);
                                } 
                                ?> 
                                
                                
                                
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>No.</th>
                            <th>User Name</th>
                            <th>Practice Name</th>
                            <th class="hidden-xs">Email</th>
                            <th class="hidden-xs hidden-sm">Phone</th>
                            <th class="hidden-xs hidden-sm">Role</th>
                            
                    </tr>
                    </tfoot>
                    </table>

                        </div>

                    </div>
                </div>
            </div>
            </div>

 
    <!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        $('.dataTables').DataTable({
            initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option><id = "init"><id/></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            },

            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Users Report'},
                {extend: 'pdf', title: 'Users Report'},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                  }
                }
            ],

        });

    });
</script>