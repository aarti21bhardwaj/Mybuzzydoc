<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h5><?= __('Plan features') ?></h5>
                <div class="text-right">
                    <?=$this->Html->link('Add New Plan Feature', ['controller' => 'planFeatures', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                </div>
            </div>


            <div class="ibox-content" style="display: block;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th class="hidden-xs hidden-sm"><?= $this->Paginator->sort('id') ?></th>
                            <th class="hidden-xs hidden-sm"><?= $this->Paginator->sort('plan_id') ?></th>
                            <th class="hidden-xs hidden-sm"><?= $this->Paginator->sort('feature_id') ?></th>
                            
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($planFeatures as $key=>$planFeature):?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td class="hidden-xs hidden-sm"><?= $this->Number->format($planFeature->id) ?></td>
                                <td class="hidden-xs hidden-sm"><?= h($planFeature->plan->id) ?></td>
                                <td class="hidden-xs hidden-sm"><?= h($planFeature->feature->id) ?></td>
                                <td class="actions">
                                    
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $planFeature->id]).' class="btn btn-xs btn-warning"">' ?>
                                <i class="fa fa-pencil fa-fw"></i>
                            </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                </ul>
                <p><?= $this->Paginator->counter() ?></p>
            </div>
        </div>
    </div>
</div>
</div>