<div class="loginColumns animated fadeInDown">
	<div class="row">
		<div class="col-md-6 text-center">

			<h2 class="font-bold m-t-lg text-muted">Welcome to BuzzyDoc</h2>
	      	<div class="profile-img-container m-t-lg">
	    		<div class="m-b-md m-t-lg">
	      
		 			<?= $this->Html->image('icon-low-rez.png', ['style' => 'width:150px; height:150px;', 'alt'=>'image'])?>
		 			</div>
	      	</div>
	        <h3>Patient and Staff Rewards</h3>
	        <a href="http://www.buzzydoc.com"><h6><i>Take me back to the BuzzyDoc website</i></h6></a>
		</div>
		<div class="col-md-6">
			<br><br><br>
			<div class="ibox-content">
				<?= $this->Form->create(null, ['class' => 'm-t']); ?>
				<div class="form-group">
					<?= $this->Form->Input('username', ['class' => 'form-control', 'label' => false, 'placeholder' => 'Username', 'required'=>'required']); ?>
				</div>
				<?= $this->Form->Input('password', ['type' => 'password', 'class' => 'form-control', 'label' => false, 'placeholder' => 'Password', 'required'=>'required']) ?>
			</div>
			<?= $this->Form->button('Login', ['type' => 'submit', 'class' => 'btn btn-primary block full-width m-b']); ?>
			<div class="row">
				<div class="text-center">
					<strong><a href="<?= $this->Url->build(['action' => 'forgotPassword'])?>"><small>Forgot password?</small></a></strong><br>
					&copy;<?php echo ' '.(date("Y")-1).'-'.date("Y").' '?>BuzzyDoc, LLC, All rights reserved.<br>
					888.696.4753 | <a href="mailto:help@buzzydoc.com">Email Us For Help</a>
				</div>
			</div>
			
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
<hr/>
</div>
