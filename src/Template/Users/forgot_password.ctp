    <div class="passwordBox animated fadeInDown">
      <div class="row">

        <div class="col-md-12">
          <div class="ibox-content">

            <h2 class="font-bold"><?= __('FORGET_PASSWORD') ?></h2>

            <p>
              <?= __('RESET_PASSWORD_LINK') ?>
            </p>

            <div class="row">

              <div class="col-lg-12">
               <?= $this->Form->create(null, ['id'=>'forgot-password-form','data-toggle'=>"validator", 'class' => 'form-horizontal']); ?>
               <div class="form-group">
                <?= $this->Form->label('name', __('Email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                <div class="col-sm-10">
                 <?= $this->Form->input('email', ['label' => false, 'placeholder'=>"Email address" ,'required' => true, 'class' => ['form-control']]); ?>
                 <div class="help-block with-errors"></div>
               </div>
             </div> 
             <div class="row text-center">
               <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
             </div>
           <?= $this->Form->end() ?>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
