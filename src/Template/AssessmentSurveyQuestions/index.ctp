<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Assessment Survey Questions for <?= h($assessmentSurvey->name)?></h5>
                </div>
                <div class="ibox-content" name="questionsAdd">

                    <?= $this->Form->create($assessmentSurveyQuestion, ['data-toggle'=>"validator", 'class' => 'form-horizontal', 'name' => 'formAddQuestion', 'url' => ['action' => 'add']]) ?>
                        <div class="form-group">

                            <?= $this->Form->label('name', __('Question Text'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                            <?= $this->Form->input('text', ['label' => false, 'placeholder' => 'Question Text','required' => true, 'type'=> 'text','class' => ['form-control']]); ?>
                            <?= $this->Form->input('assessment_survey_id', ['label' => false, 'required' => true, 'type'=> 'hidden', 'value'=> $assessmentSurvey->id]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->label('type', __('Response Group'), ['class' => ['col-sm-2', 'control-label']]); ?>
                            <div class="col-sm-10">
                                <?= $this->Form->select('response_group_id', $responseGroups ,['empty' => '--Please Select--','label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <?= $this->Form->button(__('Add Question'), ['class' => ['btn', 'btn-primary']]) ?>
                                <?= $this->Html->link('Back',['controller'=>'AssessmentSurveys', 'action' => 'index'],['class' => ['btn', 'btn-warning']]);?>
                            </div>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-hover dataTables" >
                            <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Text</th>
                                <th>Response Group</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($assessmentSurveyQuestions as $key => $question): ?>
                                <tr>
                                    <td><?= $key+1 ?></td>
                                    <td><?= $question->text ?></td>
                                    <td><?= $question->response_group->name?></td>
                                    <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'edit', $question->id]).' class="btn btn-xs btn-warning""><i class="fa fa-pencil fa-fw"></i></a>' ?>
                                    <?= $this->Form->postLink(__(''), ['action' => 'delete', $question->id], [
                                        'confirm' => __('Are you sure you want to delete # {0}?', $question->id),
                                        'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Text</th>
                                    <th>Response Group</th>
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