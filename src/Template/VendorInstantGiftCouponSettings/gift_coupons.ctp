<div class="row">
<?php if($isExpired || !isset($expiryDate)){ ?>
    <div class="middle-box text-center animated fadeInDown">
        <h2>Oops!</h2>

        <h3 class="font-bold">Seems like your available deals have <em>expired</em>.</h3><br/>
    </div>

<?php }else{ ?>

<div id="redeem">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <?= $this->Html->script('plugins/flipclock/flipclock') ?>
    <?= $this->Html->css('plugins/flipclock/flipclock') ?>
    <div class="ibox float-e-margins">
        <div class="ibox-title text-center">
            <h2 class="text-navy">Deals Available For You</h2>
            <h4>Expires in </h4><br>
            <div class="flip-clock-wrapper">
                <span id="countdown"></span>
            </div>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
           Gift Cards
        </div>
        <div class="panel-body">
        <?= $this->Flash->render() ?>
            
            <?php foreach ($giftCoupons as $giftCoupon) { ?>
            <div class="col-md-3">
                <div class="ibox">
                    <div class="ibox-content product-box">

                        <div class="product-imitation">
                            <i class = "fa fa-gift text-warning" style="font-size: 90px" ></i>
                        </div>
                        <div class="product-desc">
                            <span class="product-price">
                                <?= $giftCoupon->points ?>
                            </span>
                            <small class="text-muted">Instant Gift Coupon</small>
                            <a href="#" class="product-name"><?= $giftCoupon->description ?></a>

                            <div class="m-t text-right">
                                <a href="#" class="btn btn-xs btn-outline btn-primary" onclick="confirmRedemption(<?= $ptVisitSpending->peoplehub_user_id ?>, <?= $giftCoupon->id ?>, <?= $ptVisitSpending->vendor_id ?>)">Redeem <i class="fa fa-long-arrow-right"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <?php } ?>
        </div>
    </div>
        
</div>
<div id="thankyou" class="row">
    <div class="middle-box text-center animated fadeInDown">
        <h2>Congratulations!</h2>

        <h3 class="font-bold">Instant reward has been <em>redeemed.</em></h3><br/>
    </div>
</div>

<?php } ?>
</div>

<script type="text/javascript">
    <?php echo 'var date = "'.$expiryDate.'";'; ?>
    var clock;
    $(document).ready(function() {
        $('#thankyou').hide();
        setTimeout(function(){
            // Grab the current date
            var currentDate = new Date();
            
            var expiryDate = new Date(date*1000);
            console.log(expiryDate);
            // var utc_now = new Date(expiryDate.getUTCHours(), expiryDate.getUTCMinutes(), expiryDate.getUTCSeconds());
            console.log(expiryDate.getUTCHours());
            console.log(expiryDate.getUTCMinutes());
            // Calculate the difference in seconds between the future and current date
            // var diff = currentDate.getTime() / 1000 - expiryDate.getTime() / 1000;
            var diff = Math.abs(expiryDate.getTime() / 1000 - currentDate.getTime() / 1000);
            console.log(expiryDate.getTime());
            console.log(currentDate.getTime());

            // Instantiate a coutdown FlipClock
            clock = $('#countdown').FlipClock(diff, {
                clockFace: 'DailyCounter',
                countdown: true
            });
            
        }, 3000);
    });

</script>