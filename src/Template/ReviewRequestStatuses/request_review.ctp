 <?= $this->Form->create($reviewRequestStatus, ['data-toggle'=>'validator','class' => 'form-horizontal']) ?>
                
                <div class="form-group row">
                    <?= $this->Form->label('user_id', __('user_id'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">

                       <?= $this->Form->input('user_id', ['id' => 'uid', 'type' => 'text','label' => false, 'class' => ['form-control']]); ?>
                    </div>
                </div>

                <div class="form-group row">
                    <?= $this->Form->label('vendor_location_id', __('vendor_location_id'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">

                       <?= $this->Form->input('vendor_location_id', ['id' => 'vlid', 'type' => 'text','label' => false, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                
                <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <?= $this->Form->label('email', __('email'), ['class' => ['col-sm-2', 'control-label']]); ?>
                    <div class="col-sm-10">
                       <?= $this->Form->input('email', ['id' => 'email', 'label' => false, 'class' => ['form-control']]); ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
                <?= $this->Inspinia->horizontalRule(); ?>
                
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <ul class="list-inline">
                            <li>
                                <?= $this->Form->button(__('Submit'), ['id' => 'rvwSubmit', 'class' => ['btn', 'btn-primary']]) ?>
                            </li>
                            <li>
                                <p id="resp"></p>
                            </li>
                    </div>
                    
                </div>
               
            </div>
        <script>
        <?php
            $urlForRequest = $this->url->build(['controller' => 'Api/ReviewRequestStatuses', 'action' => 'requestReview']);
            echo 'var urlForRequest = "'.$urlForRequest.'";';
        ?>


        $('#rvwSubmit').click(function(requestReview) { 
       
            $.ajax({
                url: urlForRequest,
                dataType: 'json',
                headers:{"accept":"application/json"},                
                data: {
                    "user_id" : $('#uid').val(),
                    "email" : $('#email').val(),
                    "vendor_location_id" : $('#vlid').val(),
                },
                type: 'post',
                success: function(res){  
                    console.log(res);
                    $('#resp').html('<a href="'+res.response.url+'" id="#link" class = "btn btn-success btn-md">Review Link</a>');


                },
                error: function(err) {
                    console.log(err);
                            
                }
            });        
    	});
            </script>