var referralLeads = angular.module('referralLeads', []);

referralLeads.controller('ReferralLeadsController', function($scope, $http, $window, $filter, $element, $parse){

    $scope.awardReferralButton = false;
    $scope.showSearchCount = false;
    $scope.referralSettings = {};
    $scope.referral_peoplehub_identifier = "";
    $scope.searchQuery = "";
    $scope.referralLeadId = "";
    $scope.referrerId = "";
    $scope.referralStatus = {};
    $scope.referralLevel = {};

    getVendorReferralSettings();


    function getVendorReferralSettings(){

      $http.get($window.host + 'api/VendorReferralSettings/').then(function(response){        
        console.log('vendorReferralSettings');
        console.log(response);

        $scope.referralLevels = response.data.vendorReferralSettings;

        for(index in $scope.referralLevels){

          $scope.referralLevels[index].push({
          
            id : $scope.referralLevels[index].length , 
            referral_level_name: "Not Ready Yet"
          });
        }

      }, function(response){

        swal("Sorry", response.data.message, "error");
        console.log(response);          
      });
    } 

  this.search = function(){

    var data={
      'search-value' : $scope.searchQuery,
    }
    
    var url = $window.host+'api/users/searchPatient/'+$scope.searchQuery;
    
    $http.get(url).then(function(response){
      console.log(response);
      $scope.searchResults = response.data.data.users;
      $scope.showSearchCount = true;
      console.log($scope.searchResults);
    },function(response){
      console.log(response);
    });
  }

  this.getNewPatientId = function(vendorId, referralLeadId, referrerId){

    newLevelId = $scope.referralSettings[referralLeadId];
    idForNotReadyYet = $scope.referralLevels[vendorId][$scope.referralLevels[vendorId].length-1].id;
    levelNameForNotReady = $scope.referralLevels[vendorId][$scope.referralLevels[vendorId].length-1].referral_level_name;
    $scope.referrerId = referrerId;

    if(newLevelId == idForNotReadyYet){

      $scope.awardReferralButton = true;
        
      $http.put($window.host + 'api/ReferralLeads/'+referralLeadId, {referral_status_id: 3}).then(function(response){       
        swal("Status Updated", "", "success");
        console.log(response);
        $scope.awardReferralButton = false;
        $scope.referralStatus[referralLeadId] = angular.element( document.querySelector( '#status'+referralLeadId) );
        $scope.referralStatus[referralLeadId].text(levelNameForNotReady);

      }, function(response){

        swal("Sorry", response.data.message, "error");
        console.log(response);
        $scope.awardReferralButton = false;       
      });

    }else{

      $scope.referralLeadId = referralLeadId;
      $('#referralModal').modal('show');
    }
  }

  this.awardReferralPoints = function(newPatientId){

    swal({
                title: "Are you sure?",
                text:"This cannot be undone.",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: true
            }, function (response) {     
                if(response){

                  awardRefPoints(newPatientId);
                
                }else{

                  delete $scope.referralSettings[$scope.referralLeadId];
                }

            });

  }

  function awardRefPoints(newPatientId){

    $scope.awardReferralButton = true; 
    referralAward = {

      referral_lead_id : $scope.referralLeadId * 1,
      vendor_referral_setting_id: $scope.referralSettings[$scope.referralLeadId] * 1,
      referral_peoplehub_identifier: newPatientId * 1
    }

    $http.post($window.host + 'api/awards/referral', referralAward).then(function(response){        
       swal("Success!", "Points have been awarded.", "success");
       console.log(response);
       $scope.awardReferralButton = false;
       //Getting the new Status
       $scope.referralStatus[$scope.referralLeadId] = angular.element( document.querySelector( '#status'+$scope.referralLeadId) );
       $scope.referralStatus[$scope.referralLeadId].text(response.data.status);
       //Getting the new Level Name
       $scope.referralLevel[$scope.referralLeadId] = angular.element( document.querySelector( '#level'+$scope.referralLeadId) );
       $scope.referralLevel[$scope.referralLeadId].text(response.data.referral_level_name);
       

    }, function(response){

      swal("Sorry", response.data.message, "error");
      console.log(response); 
      $scope.awardReferralButton = false;         
    });

    

  }

});