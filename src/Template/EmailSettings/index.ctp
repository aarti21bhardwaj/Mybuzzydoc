<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Email Settings</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add New Email', ['controller' => 'EmailSettings', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                    <tr>
                            <th>No.</th>
                            <th scope="col">Layout</th>
                            <th scope="col">Template</th>
                            <th scope="col">Event</th>
                            <th scope="col">Subject</th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                           
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($emailSettings as $key => $emailSetting): ?>
                        <tr>
                            <td><?= $this->Number->format($key+1) ?></td>
                            <td><?= $emailSetting->email_layout['name'] ?></td>
                            <td><?= h($emailSetting->email_template['name']) ?></td>
                            <td><?= h($emailSetting->event['name']) ?></td>
                            <td><?= h($emailSetting->subject) ?></td>
                            <td class="actions">
                                <?= '<a href='.$this->Url->build(['action' => 'view', $emailSetting->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $emailSetting->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a> 
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>No.</th>
                            <th scope="col">Layout</th>
                            <th scope="col">Template</th>
                            <th scope="col">Event</th>
                            <th scope="col">Subject</th>
                           
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
                {extend: 'excel', title: 'Email Settings'},
                {extend: 'pdf', title: 'Email Settings'},

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