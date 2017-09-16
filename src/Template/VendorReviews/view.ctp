<?= $this->Html->css('star-rating') ?>
<?= $this->Html->script(['star-rating', 'plugins/clipboard/clipboard.min', 'reviews']) ?>
<script>
                <?php
                    $urlForNotify = $this->url->build(['controller' => 'Api/VendorReviews', 'action' => 'notify']);
                    echo 'var urlForNotify = "'.$urlForNotify.'";';
                    echo 'var vendorReviewId = "'.$vendorReview->uuid.'";';
                    echo 'var publicUrl = "'.$publicUrl.'";';
                    ?>
                
</script>
<?php

    $arr = ['google_url', 'yelp_url', 'ratemd_url','yahoo_url', 'healthgrades_url'];
    $arr2 = ['google_url'=>'google_plus', 'yelp_url'=>'yelp','ratemd_url'=> 'ratemd','yahoo_url'=> 'yahoo', 'healthgrades_url' => 'healthgrades'];
    $chkU = 1;
    foreach($arr as $ar)
    {
        if(($vendorReview['vendor_location'][$ar])){
            if($vendorReview['review_request_statuses'][0][$arr2[$ar]])
                $chkU*=1;
            else
                $chkU*=0;
        }else{

            $chkU*=1;
        }
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Your Rating & Review') ?></h5>
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
                    <h3><?= __('Thank you for telling us about your experience.') ?></h3>
                    <h4><?= __('Your Rating & Review')?></h4> 
                    <?= $this->Form->create($vendorReview) ?>
                    <?= $this->Form->input('rating', ['label' => false, 'id' => 'str', 'data-size' => 'xs', 'class' => ['form-control', 'rating-loading rating-sm']]); ?>
                    <?= $this->Form->end() ?>
                   <strong id= "rvw"><?= $vendorReview->review ?></strong> 
                <script>
                $(document).on('ready', function(){
                $('#str').rating({displayOnly: true, step: 0.5});
                });
                </script>  
            </div>
            <?php if($vendorReview->rating>=3 && $vendorReview->review !== '' && $dataHide === 1): 
                    if(($vendorReview['vendor_location']['google_url'])
                            ||($vendorReview['vendor_location']['ratemd_url'])
                            ||($vendorReview['vendor_location']['yahoo_url'])
                            ||($vendorReview['vendor_location']['yelp_url'])
                            ||($vendorReview['vendor_location']['healthgrades_url'])
                            ||($vendorReview['vendor_location']['fb_url'])  
                        ):
            ?>
            
            <div class="ibox-content">
                    <h3><?= __('Wow! What a great review!') ?></h3>
                    <h4><?= __('We have some more points we would love to give you... ') ?></h4>
                    <ul>
                    <?php if($vendorReview['vendor_location']['fb_url']): 
                            if(($vendorReview['vendor_location']['hash_tag'])):?>
                                <li><?= __('Share your review on Facebook using #'.$vendorReview->vendor_location['hash_tag'].' and earn '.$reviewSetting->fb_points.' points!')?></li>
                            <?php else: ?>
                                <li><?= __('Share your review on Facebook and earn '.$reviewSetting->fb_points.' points!')?></li>
                    <?php
                            endif;     
                        endif;
                        if(($vendorReview['vendor_location']['google_url'])):
                    ?>
                    <li><?= __('Share your review on Google+ and score another '.$reviewSetting->gplus_points.' points!') ?></li>
                    <?php 
                        endif;
                        if(($vendorReview['vendor_location']['ratemd_url'])):
                    ?>
                    <li><?= __('Share your review on RateMDs and score another '.$reviewSetting->ratemd_points.' points!') ?></li>
                    <?php 
                        endif;
                        if(($vendorReview['vendor_location']['yahoo_url'])):
                    ?>
                    <li><?= __('Share your review on Yahoo and score another '.$reviewSetting->yahoo_points.' points!') ?></li>
                    <?php 
                        endif;
                        if(($vendorReview['vendor_location']['yelp_url'])):
                    ?>
                    <li><?= __('Share your review on Yelp and score another '.$reviewSetting->yelp_points.' points!') ?></li>
                    <?php 
                        endif;
                        if(($vendorReview['vendor_location']['healthgrades_url'])):
                    ?>
                    <li><?= __('Share your review on Healthgrades and score another '.$reviewSetting->healthgrades_points.' points!') ?></li>
                    <?php 
                        endif;
                    ?>
                    </ul>
                    <?= __('(A staff member from your practice will verify your post and issue points automatically)') ?>
            </div>
            <div class="ibox-content">
                <?php 
                    $step = 1;
                    if(($vendorReview['vendor_location']['fb_url'])): 
                ?>
                    <h3><?= __('Step '.$step++.': Share On Facebook') ?></h3> 
                    <?= $this->Form->button(__('Share On Facebook'), ['id' => 'shareBtn','class' => ['btn', 'btn-md', 'btn-success']]) ?> 
                <?php 
                        endif;
                        if(($vendorReview['vendor_location']['google_url'])
                            ||($vendorReview['vendor_location']['ratemd_url'])
                            ||($vendorReview['vendor_location']['yahoo_url'])
                            ||($vendorReview['vendor_location']['yelp_url'])
                            ||($vendorReview['vendor_location']['healthgrades_url'])  
                        ):
                ?>
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <h3><?= __('Step '.$step++.': Copy Your Review') ?></h3>  
                    <?= $this->Form->button(__('Click Here to Copy Review'), ['id' => 'rvwCopyBtn','data-clipboard-target'=> '#rvw','class' => ['btn', 'btn-md', 'btn-success']]) ?>

                    <?= $this->Inspinia->horizontalRule(); ?>
                    <h3><?= __('Step '.$step++.': Paste Your Copied Review To The Following Sites') ?></h3> 
                    <ul class="list-inline">
                        <?php
                        if(($vendorReview['vendor_location']['google_url']) ):
                        ?>
                            <li>
                                <?= 
                                    $this->Html->link(__('Google +'), 
                                    $vendorReview['vendor_location']['google_url'], 
                                    ['class' => ['btn', 'btn-md', 'btn-success'], 'target' => '_blank']);
                                ?> 
                            </li>
                        <?php 
                            endif;
                            if(($vendorReview['vendor_location']['yelp_url']) ):
                         ?>
                            <li>
                                <?= 
                                    $this->Html->link(__('Yelp'), 
                                    $vendorReview['vendor_location']['yelp_url'], 
                                    ['class' => ['btn', 'btn-md', 'btn-success'], 'target' => '_blank']);
                                ?> 
                            </li>
                        <?php 
                            endif;
                            if(($vendorReview['vendor_location']['ratemd_url']) ):
                        ?>
                            <li>
                                <?= 
                                    $this->Html->link(__('RateMD'), 
                                    $vendorReview['vendor_location']['ratemd_url'], 
                                    ['class' => ['btn', 'btn-md', 'btn-success'], 'target' => '_blank']);
                                ?> 
                            </li>
                        <?php 
                            endif;
                            if(($vendorReview['vendor_location']['yahoo_url']) ):
                        ?>
                            <li>
                                <?= 
                                    $this->Html->link(__('Yahoo'), 
                                    $vendorReview['vendor_location']['yahoo_url'], 
                                    ['class' => ['btn', 'btn-md', 'btn-success'], 'target' => '_blank']);
                                ?> 
                            </li>
                        <?php 
                            endif;
                            if(($vendorReview['vendor_location']['healthgrades_url']) ):
                        ?>
                            <li>
                                <?= 
                                    $this->Html->link(__('Healthgrades'), 
                                    $vendorReview['vendor_location']['healthgrades_url'], 
                                    ['class' => ['btn', 'btn-md', 'btn-success'], 'target' => '_blank']);
                                ?>  
                            </li>
                        <?php 
                            endif;
                        ?>
                    </ul> 
                    <?= $this->Inspinia->horizontalRule(); ?>
                    <h3><?= __('Step '.$step++.': Let Your Office Know That You Have Successfully Left Reviews') ?></h3> 
                    <?= $this->Form->button(__('Notify Clinic'), ['data-target' => '#notifyModal', 'data-toggle' => 'modal', 'class' => ['btn', 'btn-md', 'btn-success']]) ?>
                    <div id="notifyModal" class="modal inmodal fade" aria-hidden="true" role="dialog" tabindex="-1" style="display: none;">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button class="close" data-dismiss="modal" type="button">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title">Notify Clinic</h4>
                                </div>
                                <div id= "modal-body"  class="modal-body">
                                <?php
                                if(($vendorReview['vendor_location']['google_url'])){
                                    if($vendorReview['review_request_statuses'][0]['google_plus'])
                                        $chkg=1;
                                    else
                                        $chk=0;
                                }else{

                                        $chk =1;
                                }
                                if($chkU === 1):?>
                                    <p><b>Clinic has already been Notified</b></p>
                                <?php else: ?>
                                    <p>
                                        <strong>Select the websites where you have posted the review:</strong>
                                        <?= $this->Form->create() ?>
                                        <?php 
                                            if(($vendorReview['vendor_location']['google_url']) && !$vendorReview['review_request_statuses'][0]['google_plus']):
                                        ?>
                                            <p>
                                        
                                                <?= $this->Form->checkbox('gplus', [ 'id'=>'gplus', 'label' => false]); ?> Google+ 
                                            </p>
                                        <?php 
                                            endif;
                                            if(($vendorReview['vendor_location']['yelp_url']) && !$vendorReview['review_request_statuses'][0]['yelp']):
                                        ?>
                                            <p>
                                                <?= $this->Form->checkbox('yelp', ['id'=>'yelp','label' => false]); ?> Yelp 
                                            </p>
                                        <?php 
                                            endif;
                                            if(($vendorReview['vendor_location']['ratemd_url']) && !$vendorReview['review_request_statuses'][0]['ratemd']):
                                        ?>
                                            <p>
                                            <?= $this->Form->checkbox('ratemd', ['id'=>'ratemd','label' => false]); ?> RateMD
                                            </p>
                                        <?php 
                                            endif;
                                            if(($vendorReview['vendor_location']['yahoo_url']) && !$vendorReview['review_request_statuses'][0]['yahoo']):
                                        ?>
                                            <p>
                                            <?= $this->Form->checkbox('yahoo', ['id'=>'yahoo','label' => false]); ?> Yahoo
                                            </p>
                                        <?php 
                                            endif;
                                            if(($vendorReview['vendor_location']['healthgrades_url']) && !$vendorReview['review_request_statuses'][0]['healthgrades']):
                                        ?>
                                            <p> 
                                            <?= $this->Form->checkbox('healthgrades', ['id'=>'healthgrades','label' => false]); ?> Healthgrades 
                                            </p> 
                                        <?php endif; ?>
                                        <?= $this->Form->end() ?>
                                        <p id= "resp"><p>
                                    </p>
                                <?php endif;?>
                                </div>
                                <div class="modal-footer">
                                    <?= $this->Form->button(__('Close'), ['id'=>'close','data-dismiss' => 'modal', 'class' => ['btn','btn-white']]) ?>
                                    <?php if(!$chkU):?>
                                        <?= $this->Form->button(__('Notify'), ['id' => 'notifyClinic','class' => ['btn','btn-primary']]) ?>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>  
                <?php endif;?>
            </div>
            <?php 
                endif;
            endif; ?>
        </div>
    </div>
</div>