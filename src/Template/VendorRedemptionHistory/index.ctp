<?php
// pr($cardSeries);die;
/**
  * @var \App\View\AppView $this
  */
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Practise Redemption History </h5>
                        <?php if(isset($vendorDepositBalances->balance)){?>
                        <dl class="dl-horizontal">
                        <dt style="width: 209px;"><?= __('Current Practise Balance') ?>:</dt> 
                            <dd><span class="label label-primary"><?= $vendorDepositBalances->balance; ?></span></dd>
                        </dl>
                        <?php }?>
                </div>

                <div class="ibox-content">

                    <div class="table-responsive">

                        <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('vendor_id') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('actual_balance') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('redeemed_amount') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('remaining_balance') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('cc_charged_amount') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('cc_transaction_identifier') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vendorRedemptionHistory as $vendorRedemptionHistory): ?>
                                    <tr>
                                        <td><?= $this->Number->format($vendorRedemptionHistory->id) ?></td>
                                        <td><?= $vendorRedemptionHistory->vendor->org_name ?></td>
                                        <td><?= $this->Number->format($vendorRedemptionHistory->actual_balance) ?></td>
                                        <td><?= $this->Number->format($vendorRedemptionHistory->redeemed_amount) ?></td>
                                        <td><?= $this->Number->format($vendorRedemptionHistory->remaining_amount) ?></td>
                                        <td><?= $this->Number->format($vendorRedemptionHistory->cc_charged_amount) ?></td>
                                        <td><?= h($vendorRedemptionHistory->cc_transaction_identifier) ?></td>
                                        <td><?= h($vendorRedemptionHistory->created) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                       <tfoot>
                        <tr>
                          <th scope="col">Id</th>
                          <th scope="col">Vendor Name</th>
                          <th scope="col">Actual Balance</th>
                          <th scope="col">Redeemed Amount</th>
                          <th scope="col">Remaining Balance</th>
                          <th scope="col">Charged Amount</th>
                          <th scope="col">Transaction Identifier</th>
                          <th scope="col">Created</th>
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
            {extend: 'excel', title: 'Vendor Redemption History'},
            {extend: 'pdf', title: 'Vendor Redemption History'},

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
