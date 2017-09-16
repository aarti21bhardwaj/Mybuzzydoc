<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                        <h5>Assessment Surveys</h5>
                </div>
                    <div class="ibox-content"> 
                        <?= $this->Form->create($vendorAssessmentSurveys, ['data-toggle'=>"validator", 'class' => 'form-inline', 'name' => 'formAdd', 'type'=>'post','url' => ['action' => 'add']]) ?>
                        <div class="form-group">
                            <?= $this->Form->label('vendor_id', __('Client'), ['class' => ['', 'control-label']]); ?>
                            <div class="">
                                <?= $this->Form->select('vendor_id', $vendors ,['empty' => '--Select Client--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('assessment_survey_id', __('Survey'), ['class' => ['', 'control-label']]); ?>
                            <div class="">
                                <?= $this->Form->select('assessment_survey_id', $assessmentSurveys ,['empty' => '--Select Survey--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                                <?= $this->Form->button(__('Assign Survey'), ['class' => ['btn', 'btn-primary']]) ?>
                            </div>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                    <br>
                    <div class="ibox-content">
                        <div class="table-responsive">   
                            <table class="table table-striped table-bordered table-hover dataTables" >
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Practice Name</th>
                                        <th class="hidden-xs">Survey Name</th>
                                        <th>Created</th>
                                        <th>Modified</th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vendorAssessmentSurveys as $key =>  $vendorAssessmentSurvey): ?>
                                        <tr>
                                            <td><?= $this->Number->format($key+1) ?></td>
                                            <td><?= h($vendorAssessmentSurvey->vendor->org_name) ?></td>
                                            <td><?= H($vendorAssessmentSurvey->assessment_survey->name) ?></td>
                                            <td><?= h($vendorAssessmentSurvey->created) ?></td>
                                            <td><?= h($vendorAssessmentSurvey->modified) ?></td>
                                            <td class="actions">
                                                <?= '<a href='.$this->Url->build(['action' => 'edit', $vendorAssessmentSurvey->id]).' class="btn btn-xs btn-warning"">' ?>
                                                <i class="fa fa-pencil fa-fw"></i>
                                            </a>
                                            <?= $this->Form->postLink(__(''), ['action' => 'delete', $vendorAssessmentSurvey->id], [
                                                'confirm' => __('Are you sure you want to delete # {0}?', $vendorAssessmentSurvey->id),
                                                'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No.</th>
                                        <th>Practice Name</th>
                                        <th>Survey Name</th>
                                        <th>Created</th>
                                        <th>Modified</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
                {extend: 'excel', title: 'Assessment Survey'},
                {extend: 'pdf', title: 'Assessment Survey'},

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


