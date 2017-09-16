<div class="lock-word animated fadeInDown" style = "z-index:0">
    <span class="first-word">LOCKED</span><span>SCREEN</span>
</div>
<div class="middle-box text-center lockscreen animated fadeInDown">
    <div>
        <div class="m-b-md">
            <?= $this->Html->image('icon-low-rez.png', ['alt' => 'BuzzyDoc', 'style' => 'height: 100px; width:100px;'])?>
        </div>
        <h3>Username : <?= $username ?></h3>
        <p>You've been successfully logged out for HIPAA reasons. Please log in with your user password to get into the app.
        </p>
        <p>You can choose to stop this from happening. To understand more about this <a href=<?= $userVoiceUrl?> id="userVoiceUrl" target="_blank">click here</a>.</p>
        <form method="post" class="m-t" action="<?= $actionUrl ?>"> 
            

            <div class="form-group">
            	<?= $this->Form->Input('username', ['class' => 'form-control', 'label' => false,'type' => 'hidden', 'value' => $username, 'required'=>'required', ]); ?>

				<?= $this->Form->Input('password', ['type' => 'password', 'class' => 'form-control', 'label' => false, 'placeholder' => '******', 'required'=>'required']) ?>
            </div>
            <?= $this->Form->button('Unlock', ['type' => 'submit', 'class' => 'btn btn-primary block full-width m-b']); ?>
        </form>
    </div>
</div>