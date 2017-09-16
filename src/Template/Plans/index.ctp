<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <h5><?= __('Plans') ?></h5>
                <div class="text-right">
                    <?=$this->Html->link('Add New Plans', ['controller' => 'plans', 'action' => 'add'],['class' => ['btn', 'btn-success']])?>
                </div>
            </div>


            <div class="ibox-content" style="display: block;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th class="hidden-xs hidden-sm"><?= $this->Paginator->sort('id') ?></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plans as $key=>$plan):?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td class="hidden-xs hidden-sm"><?= $this->Number->format($plan->id) ?></td>
                                <td><?= h($plan->name) ?></td>
                                <td class="actions">
                                    <?= '<a href='.$this->Url->build(['action' => 'view', $plan->id]).' class="btn btn-xs btn-success">' ?>
                                    <i class="fa fa-eye fa-fw"></i>
                                </a>
                                <?= '<a href='.$this->Url->build(['action' => 'edit', $plan->id]).' class="btn btn-xs btn-warning"">' ?>
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