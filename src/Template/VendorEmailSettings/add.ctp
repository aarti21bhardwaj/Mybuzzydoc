<?= $this->Html->script(['tours/emailSetting-tour']) ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 id = 'addEmail'><?= __('Add Practice Email Settings') ?></h5>
                 <div class="text-right list-inline">
                        <div>
                            <button class="btn btn-primary startTour" type="button" >
                                <i class="fa fa-play"></i> Start Tour
                            </button>
                        </div>
                </div> 
            <div class="ibox-content">
                <?= $this->Form->create($vendorEmailSetting, ['class'=>'form-horizontal']) ?>
                <?php if($loggedInUser['role']->name == 'admin'){ ?>
                  <div class="form-group">
                          <?= $this->Form->label('vendor_id', __('Practice'), ['class' => ['col-sm-2', 'control-label']]); ?>
                          <div class="col-sm-10">
                             <?= $this->Form->input('vendor_id', ['label' => false, 'class' => ['form-control'], 'options' => $vendors]); ?>
                          </div>     
                  </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <?php } else {?>
                <?= $this->Form->input('vendor_id', ['value' =>$loggedInUser['vendor_id'],'type'=>'hidden']); ?>
                <?php } ?>
                <?= $this->Inspinia->horizontalRule(); ?>
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
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="The list which you can choose from to configure your own email settings." id = 'eventName'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Event Name</label>
                    <div class="col-sm-10">
                        <?= $this->Form->input('event_id', ['onchange'=>"getEventVariables(this)",'value' => $emailSettingData->event_id,'disabled' => true,'options' => $events, 'label' => false, 'required' => true, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('from', __('From'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'from']) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('from_email', ['label' => false, 'type'=> 'email', 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('recipients', __('Recipients'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'recipients']) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('recipients', ['label' => false, 'multiple', 'pattern' => '^([\w+-.%]+@[\w-.]+\.[A-Za-z]{2,4},*[\W]*)+$','type'=> 'email', 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" data-toggle="tooltip" data-placement="top" title="Subject of the email." id = 'subject'> <i class="fa- fa fa-info-circle" style="padding-right: 5px"></i>Subject</label>
                    <div class="col-sm-10">
                        <?= $this->Form->input('subject', ['label' => false, 'class' => ['form-control'], 'value' => $emailSettingData->subject]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('body', __('Body'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'body']) ?>
                    <div class="col-sm-10">
                        <?= $this->Form->input('body', ['type' => 'textarea', 'label' => false, 'id' =>'tinymceTextarea', 'class' => ['form-control'], 'value' => $emailSettingData->body]); ?>
                    </div>
                </div>
                 <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('status', __('Status'), ['class' => ['col-sm-2', 'control-label'], 'id' => 'status']) ?>
                    <div class="col-sm-4">
                        <?= $this->Form->input('status', ['type'=>'checkbox','label' => false, 'class' => ['form-control', 'js-switch']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary'], 'id' => 'saveEmail']) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div> 
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function(){
        
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
    });

</script>

