<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title text-center">Search Patient</h3>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-lg-6 col-lg-offset-3 search-input">
        <input type="text" id="search-patient-input" class="form-control" placeholder="Enter phone number or email or card number" required>
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
    <div class="row" id="search-patient-attr">
      <div class="col-lg-6 col-lg-offset-3 text-center">
        <label class="radio-inline">
          <input type="radio" name="attrType" id="attrTypePhone" value="phone">Phone
        </label>
        <label class="radio-inline">
          <input type="radio" name="attrType" id="attrTypePhone" value="username">Username
        </label>
        <label class="radio-inline">
          <input type="radio" name="attrType" id="attrTypeEmail" value="email">Email
        </label>
        <label class="radio-inline">
          <input type="radio" name="attrType" id="attrTypeCard" value="card">Card Number
        </label>
        <label class="radio-inline">
        <input type="radio" name="attrType" id="attrTypeAll" value="">All
        </label>
      </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
  </div>
  <div class="panel-footer"><div class="row">
    <div class="col-lg-6 col-lg-offset-3 text-center">
      <button type="button" id="search-patient-btn" class="btn btn-success btn-md active search-patient">Submit</button>
    </div><!-- /.col-lg-6 -->
  </div><!-- /.row -->
</div>
<div class="panel-group" id="search-user-response-accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-success">
    <div class="panel-heading" role="tab" id="headingResponse">
      <h4 class="panel-title text-center" role="button" data-toggle="collapse" data-parent="#search-user-response-accordion" href="#collapseSearchResponse" aria-expanded="true" aria-controls="collapseSearchResponse">
       Search Response
     </h4>
   </div>
   <div id="collapseSearchResponse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingResponse">
    <div class="panel-body">
    </div>
  </div>
</div>
</div>
</div>

