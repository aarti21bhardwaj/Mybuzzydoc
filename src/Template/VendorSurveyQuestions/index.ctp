<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Practice Survey Questions</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="alert alert-success"> 
                            <strong> This is a list of your compliance survey questions. These questions will appear on each patient's account as an easy way for staff to track and record points for compliance. Default points have been set for each question but can be edited at any time.
                            </strong>    
                        </div>
                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                                <th>No.</th>
                            <?php if($loggedInUser['role']['name'] == 'admin'){ ?>
                                <th>Practice Name</th>
                            <?php } ?>
                                <th >Survey Name</th>
                                <div class="tooltip-demo">
                                    <th scope="col" data-toggle="tooltip" data-placement="top" title="Displayed on patient’s account." data-original-title="Tooltip on top"><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Survey Question</th>
                                </div>
                                <div class="tooltip-demo">
                                    <th class="select-filter" scope="col" data-toggle="tooltip" data-placement="top" title="Points awarded if the patient complies positively with survey question." data-original-title="Tooltip on top" ><i class="fa fa-lg fa-info-circle" style="padding-right: 10px"></i>Points</th>
                                </div>
                                <th class="actions"><?= __('Actions');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vendorSurveyQuestions as $key=>$vendorSurveyQuestion):?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                            <?php if($loggedInUser['role']['name'] == 'admin'){ ?>
                                <td><?= h($vendorSurveyQuestion->vendor_survey->vendor->org_name) ?></td>
                            <?php } ?>
                                <td ><?= h($vendorSurveyQuestion->vendor_survey->name) ?></td>
                                <td ><?= h($vendorSurveyQuestion->survey_question->question->text) ?></td>
                                <td class='col-sm-2'><?= $this->Form->input('points'.$vendorSurveyQuestion->id,['id'=> $vendorSurveyQuestion->id, 'value' => $vendorSurveyQuestion->points,'label'=> false,'onkeypress'=>"disAllowDotInIntegerInput(event);", 'min'=>0,"type"=>"number", 'onpaste'=>"return false;", 'required' => true,'class' => ['form-control index changepoints']]); ?></td>
                                    <td class="actions " >
                                    <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorSurveyQuestion->id]).' class="btn btn-xs btn-warning"">' ?>
                                    <i class="fa fa-pencil fa-fw"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th>No.</th>
                            <th >Practice Survey</th>
                            <th >Survey Question</th>
                           
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
                {extend: 'excel', title: 'Practice Survey Questions'},
                {extend: 'pdf', title: 'Practice Survey Questions'},

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