  var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
  var eventer = window[eventMethod];
  var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
  // Listen to message from child window
  eventer(messageEvent,function(e) {
    console.log('origin: ', e.origin)
    
    // Check if origin is proper
    /*if( e.origin != 'http://localhost' ){ return }*/
      Upwardly.loginPopup.close();
    console.log('parent received message!: ', e.data);
    Upwardly.apiLogin(e.data.token);
  }, false);


class Upwardly {

  constructor(app_id, mode='sandbox') {
    Upwardly.client_id = app_id;
    Upwardly.app_mode = mode;
    Upwardly.upUiHost = 'http://upwardly.twinspark.co/up-ui';
    Upwardly.upApiHost = 'http://upwardly.twinspark.co/dev';
    Upwardly.vendorActions = new Array();
    Upwardly.vendorActionIds = new Array();
    Upwardly.loginPopup = '';
    Upwardly.createBottomDock();
  }


  static login(provider){
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
    if(localStorage.getItem('up_token')){
      Upwardly.openUpwardlyIframe();
      return;
    }

    Upwardly.loginPopup = window.open(Upwardly.upApiHost+"/api/usersSocialLogin/login?provider="+provider+"&client_id="+Upwardly.client_id, "SignIn", "width=800,height=400");
    
  }

  static apiLogin(token){
    $.ajax({
      type: 'POST',
      url: Upwardly.upApiHost+"/api/vendorPlayers/token",
      dataType: 'json',
      headers: { 'Content-Type': 'application/json','Accept': 'application/json','Authorization' :token},
      success: function(r) {
        // createCookie('up_token',r.data.token,1);
        localStorage.setItem('up_token', r.data.token);
        $.ajax({
          type: 'GET',
          url: Upwardly.upApiHost+"/api/vendorPlayers/",
          dataType: 'json',
          headers: { 'Content-Type': 'application/json','Accept': 'application/json','Authorization' :'Bearer '+r.data.token},
          success: function(r) {
            localStorage.removeItem('up_p_i');
            localStorage.setItem('up_p_i', JSON.stringify(r.data.playerInfo));
          }
        });
        Upwardly.openUpwardlyIframe();
      }
    });
  }

  static biteMe( name, url ) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
  }
  static inArray(needle) {
    var vendorActions  = JSON.parse(localStorage.getItem('up_v_a'));
    var length = vendorActions.length;
    for(var i = 0; i < length; i++) {
      if(vendorActions[i] == needle) return true;
    }
    return false;
  }
  static push(action,options=[]){
  if(!localStorage.getItem('up_token')){
      return false;
    }
    if(Upwardly.inArray(action)){
      var vendorActions  = JSON.parse(localStorage.getItem('up_v_a'));
      var location = vendorActions.indexOf(action);
      var vendorActionsIds  = JSON.parse(localStorage.getItem('up_v_a_i'));
      options.pageTitle = $(document).find("title").text();
      options.pageUrl = window.location.href;

      //alert(JSON.stringify(options));

      $.ajax({
        method: 'POST',
        url: Upwardly.upApiHost+"/api/vendor_player_activities/",
        dataType: 'json',
        headers: { 'ContentType': 'application/json','Accept': 'application/json','Authorization' :'Bearer '+localStorage.getItem('up_token')},
        data:{
          "vendor_action_id":vendorActionsIds[location],
          "meta_data":JSON.stringify(options)
        },

        success: function(r) {
          console.log(r);
          localStorage.removeItem('up_p_i');
          localStorage.setItem('up_p_i', JSON.stringify(r.data.playerInfo.playerInfo));
          $('#rem-points').innerHTML = r.data.playerInfo.playerInfo.pointsRemainingToAchieveNextLevel;
        }
      });
    }
    //console.log( 'sending event ');

  }
    static createBottomDock() {
    $.ajax({
      method: 'GET',
      url: Upwardly.upApiHost+"/api/vendors/?client_id="+Upwardly.client_id,
      dataType: 'json',
      success: function(r) {
        for(var x in r.data.vendor.vendor_actions){
          if(r.data.vendor.vendor_actions[x].custom_action_name){
            Upwardly.vendorActions.push(r.data.vendor.vendor_actions[x].custom_action_name);
            Upwardly.vendorActionIds.push(r.data.vendor.vendor_actions[x].id);
          }else{
            Upwardly.vendorActions.push(r.data.vendor.vendor_actions[x].action.name);
            Upwardly.vendorActionIds.push(r.data.vendor.vendor_actions[x].id);
          }
        }
        console.log(Upwardly.vendorActions);
        
        localStorage.setItem('up_v_a', JSON.stringify(Upwardly.vendorActions));
        localStorage.setItem('up_v_a_i', JSON.stringify(Upwardly.vendorActionIds));

        var userImage = r.data.vendor.image_url;
        var pointsLeft = r.data.vendor.vendor_levels[0].points;
        var userName = 'Anonymous';
        if(localStorage.getItem('up_token')){
          $.ajax({
          type: 'GET',
          url: Upwardly.upApiHost+"/api/vendorPlayers/",
          dataType: 'json',
          headers: { 'Content-Type': 'application/json','Accept': 'application/json','Authorization' :'Bearer '+localStorage.getItem('up_token')},
          success: function(r) {
            localStorage.removeItem('up_p_i');
            localStorage.setItem('up_p_i', JSON.stringify(r.data.playerInfo));
            var element = document.getElementById("rem-points");
            element.innerHTML = r.data.playerInfo.pointsRemainingToAchieveNextLevel;
          }
        });
          var loggedInUser = JSON.parse(localStorage.getItem('up_p_i'));
          var userImage = loggedInUser.player.socialProfile.photo_url;
          var pointsLeft = loggedInUser.pointsRemainingToAchieveNextLevel;
          var percentPointsLeft = loggedInUser.percentPointsGainedToAchieveNextLevel;
          
          var userNameVal = loggedInUser.player.first_name+' '+loggedInUser.player.last_name;
          var userName = (userNameVal.length <= 15)? userNameVal : userNameVal+'...';

        }
        var $newProductContainer = $('<div id="up-dock" class="up-outer-container" style="bottom: 0px; position: fixed; text-align: center; width: 100%;margin-left:28%;line-height: 40px;"></div>');
        $newProductContainer.append('<div class="up-logo" onclick="Upwardly.openUpwardlyIframe();"></div>');
        $newProductContainer.append('<div class="up-start" onclick="Upwardly.OpenModal();"></div>');
        $newProductContainer.append('<div class="up-login" onclick="Upwardly.OpenModal();"></div>');

        //$newProductContainer.find('.up-logo').append('<span style="background: rgba(0, 0, 0, 0) url('+userImage+') repeat scroll center top / cover ;box-shadow: none;display: inline-block;height:40px;vertical-align: middle;width: 100%;display: inline-block; float: right;"></span>');
        $newProductContainer.find('.up-logo').append('<img style="height:60px;width:60px;" alt="image" class="pull-left" src="'+userImage+'">');

        //$newProductContainer.find('.up-logo').append('<span class="up-player-title font-bold" id="up-player-name">'+userName+'</span>');
        $newProductContainer.find('.up-logo').append('<h3 class="m-b-xs" style="margin-top: 15px;color:#fff;"><strong>'+userName+'</strong></h3>');
        

        // $newProductContainer.find('.up-start').append('<span class="up-anonymous-points up-anonymous-init" ><span id="rem-points">'+pointsLeft+ '</span> points to level up </span>');
        $newProductContainer.find('.up-start').append('<div class="font-bold"><span id="rem-points">'+pointsLeft+ '</span> points to level up <div class="progress progress-mini" style="background-color: #f3f63f;"><div style="width: '+percentPointsLeft+'%;" class="progress-bar progress-bar-warning"></div></div></div>');
        $newProductContainer.find('.up-login').append('<h3 class="m-b-xs" style="margin-top: 20px;color:#fff;"><strong>Start</strong></h3>');
        // $newProductContainer.find('.up-tweet').append('Tweet');

        $("body").append($newProductContainer);
        Upwardly.createLoginPage();
        $("body").append('<div id="up_ts_modal" class="modal" style=""><div style="background-color: #000;color: #fff;float: right;font-size: 30px;height: 52px;margin-right: 103px;margin-top: 0;cursor:pointer;" onclick="Upwardly.closeUpwardlyIframe();">&times;</div><div class="modal-content"><iframe id="iframe" name="iframe" width=100% height=600px frameborder="0" dscrolling="yes" allowtransparency="true"></iframe></div></div>');

       // $("body").append('<div id="up_su_modal" class="modal" style="width:"><div class="modal-content"><iframe id="iframe" width=100% height=600px frameborder="0" dscrolling="yes" allowtransparency="true"></iframe></div></div>');
      }
    });
  }

  static OpenModal(){

    if(localStorage.getItem('up_token')){
      Upwardly.openUpwardlyIframe();
    }else{
      Upwardly.openLoginModal();
    }
  }

  static createLoginPage() {
    var $newProductContainer = $('<div class="up-login-container"></div>');
    $newProductContainer.append('<div class="col-lg-12"><div class="fbSocial_btn"><button type="button" class="fb-btn-primary" onclick="Upwardly.login(\'Facebook\')">Login With Facebook</button></div>');
    $newProductContainer.append('<div class="col-lg-12"><div class="col-lg-6"><div class="twSocial_btn"><button type="button" class="tw-btn-primary" onclick="Upwardly.login(\'Twitter\')">Login With Twitter</button></div></div><div class="col-lg-6"><div class="gSocial_btn"><button type="button" class="g-btn-primary" onclick="Upwardly.login(\'Google\')">Login With Google</button></div></div>');
    //$newProductContainer.append('<div class="col-lg-12"><div class="gSocial_btn"><button type="button" class="g-btn-primary" onclick="Upwardly.login(\'Google\')">Login With Google</button></div>');
    $newProductContainer.append('<div class="modal-footer">Powered By <a href="javascript:void(0)">Upwardly</a></div>');
    // $("body").append($newProductContainer);

    var $newModalContainer = $('<div id="myModal" class="modal" style="display:none"><div class="modal-content" ><div class="modal-header"><div style="float:right;font-size:30px;cursor:pointer;" onclick="Upwardly.closeLoginModal();">&times;</div><h1 class="up_heading">Level Up in Octalysis </h1><div class="modal-body"><p>This is the place where you can track your growth in your journey on gamification, human-focused design, and Octalysis from my site. Top gamelites will not only receive recognition from the community, but also from me as I regularly reach out to awesome gamelites to collaborate on projects. Good luck and have fun!</p></div></div></div>');
    //var $newModalContainer = $('<div id="myModal" class="modal" style="display:none"><div class="modal-content" ><div class="modal-header"><div class="text-center m-b-md"><h1 class="pull-center font-extra-bold">Level Up in Octalysis </h1><div class="col-lg-3"></div><div class="col-lg-6"><span  class="font-extra-bold"><i class="fa fa-star fa-4x"></i>This is the place where you can track your growth in your journey on gamification, human-focused design, and Octalysis from my site. Top gamelites will not only receive recognition from the community, but also from me as I regularly reach out to awesome gamelites to collaborate on projects. Good luck and have fun!<i class="fa fa-star fa-4x"></i></span></div><div class="col-lg-3"></div></div><div class="modal-body"><p>Some text in the Modal Body</p><p>Some other text...</p></div></div></div>');
    $newModalContainer.find('.modal-body').append($newProductContainer);
    $("body").append($newModalContainer);
  }
  static openLoginModal(){
    var modal = document.getElementById('myModal');
    var element =  document.getElementById('up_ts_modal');
    if (!(typeof(element) != 'undefined' && element != null))
    {
      $("body").append('<div id="up_ts_modal" class="modal" style=""><div style="background-color: #000;color: #fff;float: right;font-size: 30px;height: 52px;margin-right: 103px;margin-top: 0;cursor:pointer;" onclick="Upwardly.closeUpwardlyIframe();">&times;</div><div class="modal-content"><iframe id="iframe" name="iframe" width=100% height=600px frameborder="0" dscrolling="yes" allowtransparency="true"></iframe></div></div>');  
    }
    modal.style.display = "block";
  }

  static closeLoginModal(){
    $('#myModal').fadeOut(500);
  }

  static closeUpwardlyIframe(){
    $('#up_ts_modal').fadeOut(500);
  }
  
  static openUpwardlyIframe(url = null){

    var token = localStorage.getItem('up_token');
    if(token){
      token = '?token='+token+"&client_id="+Upwardly.client_id;
    }else{
      token = "?client_id="+Upwardly.client_id;
    }
    if(!url){
      var url = Upwardly.upUiHost+token;
    }
    $('#up_ts_modal iframe').attr('src', url);
    $('#up_ts_modal').fadeIn(500);
  }



};
