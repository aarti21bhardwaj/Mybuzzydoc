<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">

        <div class="ibox-content">

          <div class="table-responsive">

            <table class="display responsive no-wrap table table-striped table-bordered table-hover dataTables" width="100%">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Practice Name</th>
                  <th>Cards</th>
                  <th class="hidden-xs">Email</th>
                  <th class="hidden-xs hidden-sm">Phone</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($response->users as $key => $user):?>
                  <tr>
                    <td><?= $this->Number->format($key+1) ?></td>
                    <td><?= h($user->name) ?></td>
                    <td><?php
                    foreach ($user->vendor_users as $key1 => $value) {
                      echo ($value->vendor->name)."<br/>";
                    }
                    ?>
                  </td>
                  <td><?php
                  foreach ($user->user_cards as $key1 => $value) {
                    echo ($value->card_number)."<br/>";
                  }
                  ?>
                </td>
                    <td class="hidden-xs"><?= h($user->email) ?></td>
                    <td class="hidden-xs hidden-sm"><?= h($user->phone) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
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
        {extend: 'excel', title: 'Users Report'},
        {extend: 'pdf', title: 'Users Report'},

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
