<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= __('Practice Settings') ?></h5>
                 <label class="pull-right"><?= h($vendor->org_name)?></label>
            </div>

            <div class="ibox-content" style="display: block;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Key</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?= $this->Form->create(null, ['class' => 'form-horizontal']) ?>
                    <?php foreach ($settingKeys as $key => $settingkey): ?>
                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= $settingkey['name'] ?></td>
                                <td>
                                <div class = 'form-group'>
                                <?php  
                                        if ($settingkey['type'] == 'boolean'){

                                            if(!$settingkey['vendor_settings']){
                                                $yes='';
                                                $no='';
                                            }else{
                                             
                                                if($settingkey['vendor_settings'][0]['value'] == 1){
                                                    $yes = 'checked=""';
                                                    $no = '';
                                                }else{
                                                    $yes = '';
                                                    $no = 'checked=""';
                                                }
                                            }

                                            $disabled  = false;
                                            if($settingkey['name'] == "Instant Redeem"){
                                                $disabled  = 'disabled';
                                            }
                                            echo '<div class="radio radio-danger radio-inline">    
                                                    <input type="radio" '.$yes.' name="'.$settingkey['id'].'" id="radio'.$key.'" value="1" '.$disabled.' > 
                                                    <label for="radio'.$key.'"> Yes </label> 
                                                </div>
                                                <div class="radio radio-danger radio-inline">    
                                                    <input type="radio" '.$no.' name="'.$settingkey['id'].'" id="radio1'.$key.'" value="0" '.$disabled.'> 
                                                    <label for="radio1'.$key.'"> No </label> 
                                                </div>';

                                        }elseif($settingkey['type'] == 'credit_type'){

                                            if(!$settingkey['vendor_settings']){
                                                $store='';
                                                $credit='';
                                            }else{
                                             
                                                if($settingkey['vendor_settings'][0]['value'] == 'store_credit'){
                                                    $store = 'checked=""';
                                                    $credit = '';
                                                }else{
                                                    $store = '';
                                                    $credit = 'checked=""';
                                                }
                                            }

                                            echo '<div class="radio radio-danger radio-inline">    
                                                    <input type="radio" '.$store.' name="'.$settingkey['id'].'" id="radio'.$key.'" value="store_credit"> 
                                                    <label for="radio'.$key.'"> Store </label> 
                                                </div>
                                                <div class="radio radio-danger radio-inline">    
                                                    <input type="radio" '.$credit.' name="'.$settingkey['id'].'" id="radio1'.$key.'" value="wallet_credit"> 
                                                    <label for="radio1'.$key.'"> Wallet </label> 
                                                </div>';
                                        }
                                        else{
                                            if(!$settingkey['vendor_settings'])
                                            {
                                                $value = '';
                                            }else{
                                                $value = $settingkey['vendor_settings'][0]['value'];
                                            }
                                            echo $this->Form->input($settingkey['id'], ['label' => false, 'class' => ['form-control'], 'placeholder'=> 'Points', 'value' => $value ]); 
                                        }
                                ?>
                                </div> 
                                </td>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $this->Inspinia->horizontalRule(); ?>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <?= $this->Form->button(__('Submit'), ['class' => ['btn', 'btn-primary']]) ?>
                        <?= $this->Html->link('Cancel',$this->request->referer(),['class' => ['btn', 'btn-danger']]);?>
                    </div>
                </div>
                <?= $this->form->end() ?>
        </div>
    </div>
</div>
</div>