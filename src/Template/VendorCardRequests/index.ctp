<?php
// pr();die;
/**
  * @var \App\View\AppView $this
  */
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Issue Card Requests</h5>
                    <?php if($loggedInUserRole != 'admin'){ ?>
                    <div class="text-right">
                        <?=$this->Html->link('Request More Cards', ['controller' => 'VendorCardRequests', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                    </div>
                    <?php } ?>
                </div>

                <div class="ibox-content">

                    <div class="table-responsive">

                        <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Series</th>
                                    <th scope="col">Count</th>
                                    <th scope="col">Is Issued</th>
                                    <th scope="col">Request Date</th>
                                     <?php if($loggedInUserRole == 'admin'){ ?>
                                     <th scope="col">Start</th>
                                    <th scope="col">Upto</th>
                                    <th scope="col">Is Rejected</th>
                                    <th scope="col">Remarks</th>
                                    <?php } ?>
                                    <th scope="col" class="actions"><?= __('Actions') ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php  foreach ($vendorCardRequests as $key => $request): ?>
                                    <tr>
                                        <td><?= $this->Number->format($key+1) ?></td>
                                        <!-- <td><?= $this->Number->format($trainingVideo->id) ?></td> -->
                                        <td><?= h($request->vendor_card_series) ?></td>
                                        <td><?= h($request->count) ?></td>
                                        <td><?= h($request->is_issued ? 'Yes' : 'No')  ?></td>
                                        <td><?= h($request->created) ?></td>
                                        <?php if($loggedInUserRole == 'admin'){ ?>
                                        <td><?= h($request->start) ?></td>
                                        <td><?= h($request->end) ?></td>
                                        <td><?= h(!$request->status ? 'No' : 'Yes') ?></td>
                                        <td><?= h($request->remark) ?></td>
                                         <?php } ?>
                                        <!-- <td><?= h($trainingVideo->embedded_source) ?></td> -->
                                        <td class="actions">
                                            <?= '<a href='.$this->Url->build(['action' => 'view', $request->id]).' class="btn btn-sm btn-success fa fa-eye fa-fh">' ?>
                                        </a>
                                        <?php if($request->status && !$request->is_issued){?>

                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $request->id]).' class="btn btn-sm btn-warning fa fa-pencil fa-fh">' ?>
                                            <?php }?>
                                    </a>
                                    <?php if($loggedInUserRole == 'admin'){ 
                                    if($request->status && !$request->is_issued){ 
                                       echo $this->Form->postLink(__(''), ['action' => 'issueCards', $request->id], [
                                        'confirm' => __('You are going to issue requested cards of series "'.$request->vendor_card_series.'?'),
                                        'class' => ['btn', 'btn-sm', 'btn-primary', 'fa', 'fa-check-circle-o', 'fa-fh']]);
                                        } 
                                     }?>
                                    <?= $this->Form->postLink(__(''), ['action' => 'delete', $request->id], [
                                        'confirm' => __('Are you sure you want to delete this request # {0}?', $request->id),
                                        'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?> 
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                         <th scope="col">No.</th>
                         <th scope="col">Video Title</th>
                         <!--  <th scope="col">Video Embedded Source</th> -->
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
            {extend: 'excel', title: 'Training Videos'},
            {extend: 'pdf', title: 'Training Videos'},

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
