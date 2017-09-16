<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Email Settings</h5>
                        <!-- <div class="text-right">
                        <?=$this->Html->link('Add New Practice Email', ['controller' => 'defaultEmailSettings', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div> -->
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success"> 
                            <strong>Default emails have been setup for your convenience. You can customize your own email templates and settings for any event from the provided list.
                            </strong>    
                        </div>

                        <div class="table-responsive">
                        
                            <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <!-- <th scope="col">Practice Name</th> -->
                                    <!-- <th scope="col">Layout</th>
                                    <th scope="col">Template</th> -->
                                    <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title=" The list which you can choose from to configure your own email settings." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Event</th>
                                    </div>
                                    <div class="tooltip-demo">
                                        <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="  Subject of the email." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Subject</th>
                                    </div>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($defaultEmailSettings as $key => $defaultEmailSetting):?>
                                <tr>

                                <!-- Here check if vendor has set any custom email settings, if yes then the row will reflect the data from VendorEmailSettings Table else it'll show the data from EmailSettings Table. The view button will work according to the afore mentioned condition. The Edit button will take you to edit if the setting has been customised for that event else it'll take you to add new setting show default values in the input fields. The delete button will be shown only in case of customised settings in VendorEmailSettings Table and clicking on it will delete the entry from the VES table and the setting will go back to default. -->
                                    <?php if(isset($vendorEmailSetting[$defaultEmailSetting->event_id])){   ?>
                                    <td><?= $this->Number->format($key+1) ?></td>
                                   <!--  <td><?= h($defaultEmailSetting->vendor->org_name) ?></td> -->
                                    <!-- <td><?= h($vendorEmailSetting[$defaultEmailSetting->event_id]->email_layout['name']) ?></td>
                                    <td><?= h($vendorEmailSetting[$defaultEmailSetting->event_id]->email_template['name']) ?></td> -->
                                    <td><?= h($vendorEmailSetting[$defaultEmailSetting->event_id]->event['name']) ?></td>
                                    <td><?= h($vendorEmailSetting[$defaultEmailSetting->event_id]->subject) ?></td>
                                    <td><?= ($vendorEmailSetting[$defaultEmailSetting->event_id]->status) ? 'Enabled' : 'Disabled' ?></td>
                                    <td class="actions">
                                        <?php 

                                            echo '<a href='.$this->Url->build(['action' => 'view', $vendorEmailSetting[$defaultEmailSetting->event_id]->id]).' class="btn btn-xs btn-success">';
                                                echo '<i class="fa fa-eye fa-fw"></i>';
                                            echo '</a>';
                                           echo '<a href='.$this->Url->build(['action' => 'edit', $vendorEmailSetting[$defaultEmailSetting->event_id]->id]).' class="btn btn-xs btn-warning"">
                                                    <i class="fa fa-pencil fa-fw"></i>
                                                </a>';

                                            echo $this->Form->postLink(__(''), ['action' => 'delete', $vendorEmailSetting[$defaultEmailSetting->event_id]->id], [
                                                'confirm' => __('Are you sure you want to delete # {0}?', $vendorEmailSetting[$defaultEmailSetting->event_id]->id),
                                                'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]);
                                       }else{                                                           ?>

                                    <td><?= $this->Number->format($key+1) ?></td>
                                   <!--  <td><?= h($defaultEmailSetting->vendor->org_name) ?></td> -->
                                    <!-- <td><?= h($defaultEmailSetting->email_layout['name']) ?></td>
                                    <td><?= h($defaultEmailSetting->email_template['name']) ?></td> -->
                                    <td><?= h($defaultEmailSetting->event['name']) ?></td>
                                    <td><?= h($defaultEmailSetting->subject) ?></td>
                                    <td> Enabled </td>
                                    <td class="actions">

                                    <?php
                                            echo '<a href='.$this->Url->build(['controller' => 'EmailSettings' ,'action' => 'view',$defaultEmailSetting->id]).' class="btn btn-xs btn-success">';
                                                echo '<i class="fa fa-eye fa-fw"></i>';
                                            echo '</a>';
                                           echo '<a href='.$this->Url->build(['action' => 'add',$defaultEmailSetting->id]).' class="btn btn-xs btn-warning"">';
                                           echo '<i class="fa fa-pencil fa-fw"></i>
                                            </a>';
                                       }
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <!-- <th scope="col">Practice Name</th> -->
                                   <!--  <th scope="col">Layout</th>
                                    <th scope="col">Template</th> -->
                                    <th scope="col">Event</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Status</th>
                                   
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
                {extend: 'excel', title: 'Practice Email Settings'},
                {extend: 'pdf', title: 'Practice Email Settings'},

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