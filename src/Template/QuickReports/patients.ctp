
 <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Average Number Of Patients Recieving Points (per day over 30 days)</h5>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                        
                    <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Vendor Name</th>
                            <th scope="col">No. Of Patients</th>
                            <th scope="col">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php foreach ($vendors as $key=>$value):

                                    if($value->people_hub_identifier){
                                        $peopleHubId =  $value->people_hub_identifier;    
                                    }elseif($value->sandbox_people_hub_identifier){
                                        $peopleHubId =  $value->sandbox_people_hub_identifier;
                                    }else{
                                        $peopleHubId = false;
                                    }
                            ?>
                            <tr>
                                <td><?= $this->Number->format($key+1) ?></td>
                                <td><?= h($value->org_name) ?></td>
                                <td><?=  h($peopleHubId && isset($average[$peopleHubId]) ? $average[$peopleHubId] : 0) ?></td>
                                <td><?=  $fromDate.' ' .'-'.' ' .$toDate ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Vendor Name</th>
                        <th scope="col">No. Of Patients</th>
                        <th scope="col">Duration</th>
                    </tr>
                    </tfoot>
                    </table>

                        </div>

                    </div>
                </div>
            </div>
            </div>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        $('.dataTables').DataTable({
            initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option><id = "init"><id/></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            },

            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'No. of Patients receiving points'},
                {extend: 'pdf', title: 'No. of Patients receiving points'},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                  }
                }
            ],

        });

    });
</script>