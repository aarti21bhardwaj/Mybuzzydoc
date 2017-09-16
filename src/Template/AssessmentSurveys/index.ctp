<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Assessment Surveys</h5>
                </div>
                <div class="ibox-content">

                    <?= $this->Form->create($assessmentSurvey, ['data-toggle'=>"validator", 'class' => 'form-inline', 'name' => 'formAdd', 'url' => ['action' => 'add']]) ?>
                        <div class="form-group">
                            <?= $this->Form->label('name', __('Survey Name'), ['class' => ['', 'control-label']]); ?>
                            <div class="">
                                <?= $this->Form->input('name', ['label' => false, 'placeholder' => 'name','required' => true, 'class' => ['form-control']]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('type', __('Survey Type'), ['class' => ['', 'control-label']]); ?>
                            <div class="">
                                <?= $this->Form->select('survey_type_id', $surveyTypes ,['empty' => '--Please Select--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                                <?= $this->Form->button(__('Add Survey'), ['class' => ['btn', 'btn-primary']]) ?>
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
                                    <th class="hidden-xs">Survey Name</th>
                                    <th>Survey Type</th>
                                    <th>Created</th>
                                    <th>Modified</th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assessmentSurveys as $key =>  $assessmentSurvey): ?>
                                    <tr>
                                        <td><?= $this->Number->format($key+1) ?></td>
                                        <td><?= H($assessmentSurvey->name) ?></td>
                                        <td><?= h($assessmentSurvey->survey_type->name) ?></td>
                                        <td><?= h($assessmentSurvey->created) ?></td>
                                        <td><?= h($assessmentSurvey->modified) ?></td>
                                        <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $assessmentSurvey->id]).' class="btn btn-xs btn-warning""><i class="fa fa-pencil fa-fw"></i></a>' ?>
                                        <?= $this->Form->postLink(__(''), ['action' => 'delete', $assessmentSurvey->id], [
                                        'confirm' => __('Are you sure you want to delete # {0}?', $assessmentSurvey->id),
                                        'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Survey Name</th>
                                    <th>Survey Type</th>
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


