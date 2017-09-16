<div class="row">
    <div class="col-lg-9">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-b-md">
                                <h2><?= h($vendorLocation['vendor']['org_name']) ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <dl class="dl-horizontal">
                                <dt><?= __('Address') ?>:</dt> <dd> <?= h($vendorLocation->address) ?> </dd>
                                <dt><?= __('Facebook URL') ?>:</dt> <dd style="word-wrap:break-word;"> <?= h($vendorLocation->fb_url) ?> </dd>
                                <dt><?= __('Google Plus URL') ?>:</dt> <dd style="word-wrap:break-word;"> <?= h($vendorLocation->google_url) ?> </dd>
                                <dt><?= __('Yelp URL') ?>:</dt> <dd style="word-wrap:break-word;"> <?= h($vendorLocation->yelp_url) ?> </dd>
                                <dt><?= __('RateMD URL') ?>:</dt> <dd style="word-wrap:break-word;"> <?= h($vendorLocation->ratemd_url) ?> </dd>
                                 <dt><?= __('Yahoo URL') ?>:</dt> <dd style="word-wrap:break-word;"> <?= h($vendorLocation->yahoo_url) ?> </dd>
                                <dt><?= __('Healthgrades URL') ?>:</dt> <dd style="word-wrap:break-word;"> <?= h($vendorLocation->healthgrades_url) ?> </dd>
                                <dt><?= __('HashTag') ?>:</dt> <dd> <?= h($vendorLocation->hash_tag) ?> </dd>
                                <dt><?= __('Default') ?>:</dt> <dd> <?= h($vendorLocation->is_default) ? 'Yes' : 'No' ?> </dd>
                            </dl>
                        </div>
                    </div> 
                    
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <?= $this->Html->link('Back',$this->request->referer(),['id'=>'bk', 'class' => ['btn', 'btn-warning']]);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
