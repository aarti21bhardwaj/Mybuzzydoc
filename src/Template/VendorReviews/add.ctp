<?= $this->Html->css('star-rating') ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('First Step: Please Rate our practice.') ?></h5>
            </div>
            
            <div class="ibox-content">
                <ul class = "list-inline">
                    <li>
                        <?= $this->Html->image('logo.png') ?>
                    </li>
                    <li>
                        <h3><?= __($vendorLocation['vendor']['org_name']) ?></h3>
                        <h4><?= __($vendorLocation['address']) ?></h4>
                    </li>
                </ul>
                <hr>
                <?= $this->Form->create($vendorReview, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
                <strong class="col-sm-offset-2">Rate Us:</strong>
                <div class="form-group row">
                    <?= $this->Form->label('', __(''), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->hidden('vendor_location_id') ?>
                       <?= $this->Form->input('rating', ['id'=> 'ratingVal', 'label' => false, 'class' => ['rnr','form-control', 'rating']]); ?>
                    </div>
                </div>
                
                <?= $this->Inspinia->horizontalRule(); ?>
                <strong class="col-sm-offset-2">Review Us:</strong> What do you like about our practice?<br>
                <strong class="col-sm-offset-2">Ideas:</strong> Love the staff; The doctor is really nice; Wouldn't have gone anywhere else."
                <div class="form-group">
                    <?= $this->Form->label('', __(''), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('review', ['id'=> 'rvwVal','label' => false, 'class' => ['rnr','form-control']]); ?>
                    </div>
                </div>
                <?= $this->Inspinia->horizontalRule(); ?>
                
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <?= $this->Form->button(__('Submit'), ['id' => 'rvwSubmit','class' => ['btn', 'btn-primary']]) ?>
                    </div>
                </div>     
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script('star-rating') ?>
<script type="text/javascript">
    $.fn.ratingLocales.en.clearCaption = 'Not Rated Yet';
</script>