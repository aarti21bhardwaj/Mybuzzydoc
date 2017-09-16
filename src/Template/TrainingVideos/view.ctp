<div class="row">
    <div class="col-lg-9">
        <div class="wrapper wrapper-content animated fadeInUp">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-b-md col-sm-6">
                                <h2><?= h($trainingVideo->title) ?></h2>
                            </div>
                            <div class="text-right col-sm-6">
                                    <div>
                                        <?= $this->Html->link('Back',$this->request->referer(),['class' => ['btn', 'btn-md','btn-warning']]);?>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <div><?= $trainingVideo->embedded_source ?></div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>