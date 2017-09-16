<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Average Points Awarded (per day over 30 days)</h5>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Vendor Name</th>
                            <th scope="col">Points</th>
                            <th scope="col">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php foreach ($quickReports as $key=>$quickReport):
                            $manualAwards = $quickReport->manual_awards ? $quickReport->manual_awards[0]->totalPoints : null;
                            $milestoneLevelAwards = ($quickReport->milestone_level_awards) ? $quickReport->milestone_level_awards[0]->totalPoints : null;
                            $referralAwards = ($quickReport->referral_awards) ? $quickReport->referral_awards[0]->totalPoints : null;
                            $referralTierAwards = ($quickReport->referral_tier_awards) ? $quickReport->referral_tier_awards[0]->totalPoints : null;
                            $reviewAwards = ($quickReport->review_awards) ? $quickReport->review_awards[0]->totalPoints : null;
                            $surveyAwards = ($quickReport->survey_awards) ? $quickReport->survey_awards[0]->totalPoints : null;
                            $tierAwards = ($quickReport->tier_awards) ? $quickReport->tier_awards[0]->totalPoints : null;
                            $promotionAwards = $quickReport->promotion_awards ? $quickReport->promotion_awards[0]->totalPoints : null;

                            $average = ($promotionAwards+ $manualAwards + $milestoneLevelAwards + $referralAwards + $referralTierAwards + $reviewAwards + $surveyAwards + $tierAwards + $promotionAwards)/30;

                            $average = round((float)$average, 2)

                            ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($quickReport->org_name) ?></td>
                                <td><?=  h($average) ?></td>
                                <td><?=  $fromDate.' ' .'-'.' ' .$toDate ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Vendor Name</th>
                        <th scope="col">Points</th>
                        <th scope="col">Duration</th>
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
                {extend: 'excel', title: 'Average number of points awarded'},
                {extend: 'pdf', title: 'Average number of points awarded'},

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