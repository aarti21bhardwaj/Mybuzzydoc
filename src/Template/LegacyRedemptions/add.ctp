<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class = "ibox-title">
                <h5><?= __('Add Redemption') ?></h5>
            </div>
            <div class = "ibox-content">
                <?= $this->Form->create($legacyRedemption, ['class' => 'form-horizontal']) ?>
                    <div class="form-group">
                      <?php $this->Form->templates([
                    'label' => '<label{{attrs}}>{{text}}<sup><small><i class="fa fa-asterisk text-danger"></i></small></sup></label>' ]); ?>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('legacy_reward_id', __('Legacy Reward'), ['class' => ['col-sm-2', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('legacy_reward_id', ['label' => false,'required' => true,'class' => ['form-control'], 'options' => $legacyRewards]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('vendor_id', __('Practice Name'), ['class' => ['col-sm-2', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('vendor_id', ['label' => false,'required' => true,'class' => ['form-control'], ]); ?>
                        </div>
                    </div>
                        <?= $this->Form->input('legacy_redemption_status_id', ['options' => $legacyRedemptionStatuses]); ?>
                        <?= $this->Form->input('transaction_number'); ?>
                        <?= $this->Form->input('redeemer_name'); ?>
                        <?= $this->Form->input('redeemer_peoplehub_identifier'); ?>
                
                    
                <?= $this->Form->button(__('Submit')) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
