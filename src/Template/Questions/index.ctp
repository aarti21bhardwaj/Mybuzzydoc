<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Questions</h5>
                        <div class="text-right">
                        <?=$this->Html->link('Add Question', ['controller' => 'Questions', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Text</th>
                            <th scope="col">Question Type</th>
                            <th scope="col">Frequency</th>
                            <th scope="col">Points</th>
                            <th scope="col" class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($questions as $question): ?>
                        <tr>
                            <td><?= $this->Number->format($question->id) ?></td>
                            <td><?= h($question->text) ?></td>
                                <!-- <td><?= $question->has('question_type') ? $this->Html->link($question->question_type->name, ['controller' => 'QuestionTypes', 'action' => 'view', $question->question_type->id]) : '' ?></td> -->
                                <td class="hidden-xs"><?= h($question->question_type->name) ?></td>
                                <td><?= $this->Number->format($question->frequency) ?></td>
                                <td><?= $this->Number->format($question->points) ?></td>
                                <!-- <td><?= h($question->created) ?></td>
                                <td><?= h($question->modified) ?></td> -->
                                <td class="actions">
                                        <?= '<a href='.$this->Url->build(['action' => 'view', $question->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $question->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                                </a>
                                <?= $this->Form->postLink(__(''), ['action' => 'delete', $question->id], [
                                    'confirm' => __('Are you sure you want to delete # {0}?', $question->id),
                                    'class' => ['btn', 'btn-sm', 'btn-danger', 'fa', 'fa-trash-o', 'fa-fh']]) ?>
                                    <!-- <?= $this->Html->link(__('View'), ['action' => 'view', $question->id]) ?>
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $question->id]) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $question->id], ['confirm' => __('Are you sure you want to delete # {0}?', $question->id)]) ?> -->
                                </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Text</th>
                            <th scope="col">Question Type</th>
                            <th scope="col">Frequency</th>
                            <th scope="col">Points</th>
                           
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
                {extend: 'excel', title: 'Questions'},
                {extend: 'pdf', title: 'Questions'},

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