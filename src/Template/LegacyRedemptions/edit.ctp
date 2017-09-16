<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class = "ibox-title">
                <h5><?= __('Edit Redemption') ?></h5>
            </div>
            <div class = "ibox-content">
                <?= $this->Form->create($legacyRedemption, ['data-toggle'=>"validator", 'class' => 'form-horizontal']) ?>
                    <div class="form-group">
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('legacy_reward_id', __('Legacy Reward'), ['class' => ['col-sm-3', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('legacy_reward_id', ['label' => false,'required' => true, 'disabled'=>true, 'class' =>['form-control'], 'options' => $legacyRewards]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('vendor_id', __('Practice Name'), ['class' => ['col-sm-3', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('vendor_id', ['label' => false,'required' => true, 'disabled'=>true,'class' => ['form-control'], ]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('legacy_redemption_status_id', __('Redemptions Status'), ['class' => ['col-sm-3', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('legacy_redemption_status_id', ['label' => false,'required' => true, 'class' => ['form-control'], 'options' => $legacyRedemptionStatuses ]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('transaction_number', __('Transaction Number'), ['class' => ['col-sm-3', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('transaction_number', ['label' => false,'required' => true, 'disabled'=>true,'class' => ['form-control']]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('redeemer_name', __('Redeemer Name'), ['class' => ['col-sm-3', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('redeemer_name', ['label' => false,'required' => true, 'disabled'=>true,'class' => ['form-control'], "readonly"=>"readonly"]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <div class="form-group">
                        <?= $this->Form->label('redeemer_peoplehub_identifier', __('Redeemer Peoplehub Identifier'), ['class' => ['col-sm-3', 'control-label']]) ?>
                        <div class="col-sm-4">
                            <?= $this->Form->input('redeemer_peoplehub_identifier', ['label' => false,'required' => true, 'disabled'=>true,'class' => ['form-control']]); ?>
                        </div>
                    </div>
                    <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>  
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>      
                    </div>
                </div>
             <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
