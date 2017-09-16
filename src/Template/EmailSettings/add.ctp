<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Add Email Settings') ?></h5>
            </div>
            <div class="ibox-content">
                <?= $this->Form->create(null, ['class'=>'form-horizontal']) ?>
                <div class="form-group">
                        <?= $this->Form->label('email_layout_id', __('Layout'), ['class' => ['col-sm-2', 'control-label']]); ?>
                        <div class="col-sm-10">
                           <?= $this->Form->input('email_layout_id', ['label' => false, 'class' => ['form-control'], 'options'=> $emailLayouts]); ?>
                        </div>     
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('email_template_id', __('Template'), ['class' => ['col-sm-2', 'control-label']]) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('email_template_id', ['label' => false, 'class' => ['form-control'], 'options'=> $emailTemplates]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('event_id', __('Event Name'), ['class' => ['col-sm-2', 'control-label']]) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('event_id', ['onchange'=>"getEventVariables(this)",'empty' => '--Please Select--','options' => $events, 'label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('from', __('From'), ['class' => ['col-sm-2', 'control-label']]) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('from_email', ['label' => false, 'type'=> 'email', 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('recipients', __('Recipients'), ['class' => ['col-sm-2', 'control-label']]) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('recipients', ['label' => false, 'multiple', 'pattern' => '^([\w+-.%]+@[\w-.]+\.[A-Za-z]{2,4},*[\W]*)+$','type'=> 'email', 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('subject', __('Subject'), ['class' => ['col-sm-2', 'control-label']]) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('subject', ['label' => false, 'class' => ['form-control'], 'required' => true]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('body', __('Body'), ['class' => ['col-sm-2', 'control-label']]) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('body', ['type'=> 'textarea', 'label' => false,'id' =>'tinymceTextarea', 'class' => ['form-control'], 'required' => 'true']); ?>
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