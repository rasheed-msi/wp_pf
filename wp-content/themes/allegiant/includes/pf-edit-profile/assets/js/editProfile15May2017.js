	'use strict';

var api_url = '';
var access_token = '';
//console.log(access_token);
var aboutusGetUrl = api_url+"v1/editprofile/aboutus?access_token=" + access_token;
var contactUsGetUrl = api_url+"v1/editprofile/contactus?access_token=" + access_token;
var childPrefGetUrl = api_url+"v1/editprofile/childpreference?access_token=" + access_token;
var aboutusPostUrl = api_url+"v1/editprofile/save/aboutus";
var contactusPostUrl = api_url + "v1/editprofile/save/contactus";
var childPrefPostUrl = api_url + "v1/editprofile/save/childpreference"; 
var getStatesUrl = api_url + "v1/editprofile/get/states";

var app = angular.module('ui.bootstrap.demo', ['ngAnimate', 'ngSanitize', 'ngRoute','ui.bootstrap', 'ui.mask']);
app.config(['$qProvider', function ($qProvider) {
    $qProvider.errorOnUnhandledRejections(false);
}]);
app.controller('TabsCtrl', ['$scope','$http','$location',function ($scope,$http, $window, $location) {
	$scope.model = {
	    name: 'Tabs'
	  };
  	   $scope.$watch('access_token', function () {
		    access_token = $scope.access_token;

	   });
	   $scope.$watch('site_url',function(){
	   		api_url = $scope.site_url;
	   		aboutusGetUrl = api_url+"v1/editprofile/aboutus?access_token=" + access_token;
			contactUsGetUrl = api_url+"v1/editprofile/contactus?access_token=" + access_token;
			childPrefGetUrl = api_url+"v1/editprofile/childpreference?access_token=" + access_token;
			aboutusPostUrl = api_url+"v1/editprofile/save/aboutus";
			contactusPostUrl = api_url + "v1/editprofile/save/contactus";
			getStatesUrl = api_url + "v1/editprofile/get/states"
			childPrefPostUrl = api_url + "v1/editprofile/save/childpreference"; 
	   });

	  $scope.model.tabs = [
			{
				title: "About Us",
				templateUrl: 'assets/views/about_us.html',
				isLoaded : false
			},
			{
				title: "Child Preferences",
				templateUrl: 'assets/views/about_child.html',
				isLoaded : false
			},
			{
				title: "Contact Info",
				templateUrl: 'assets/views/contact_info.html',
				isLoaded : false
			}
			
			
	
		];
		//$scope.tabContent = $scope.model.tabs[1].templateUrl;
		$scope.model.tabs[0].isLoaded = true;    
		$scope.onTabSelected = function(index) {
		  $scope.model.tabs[index].isLoaded = true;          
        };		

}]);

app.controller('aboutUsController', ['$scope','$http',function ($scope,$http, $window) {
		//$qProvider.errorOnUnhandledRejections(false);
		$scope.dt = new Date();
		$scope.dt2 = new Date();
		$scope.open1 = function() {
			$scope.popup1.opened = true;
		};
		$scope.popup1 = {
			opened: false
		};
		$scope.open2 = function() {
			$scope.popup2.opened = true;
		};
		$scope.popup2 = {
			opened: false
		};
		$scope.dateOptions = {
			minDate: "12/31/1901",
			maxDate: new Date()
		};
		$scope.dateStyle = '';
		$scope.validateDate = function(){
		    if($scope.dt != ''){
		    	var dateType = angular.isDate($scope.dt);
		    	if(dateType == true){
				$scope.dateErr = 0;
		    	}else if(dateType == false){
				$scope.dateErr	 = 1;
		    	}
			if($scope.dt > new Date() || $scope.dt < new Date("12/31/1900"))
			  	$scope.dateErr	 = 1;
			console.log(new Date("12/31/1901"));

		    }
		    if($scope.dt2 != ''){
		    	var dateType = angular.isDate($scope.dt2);
		    	if(dateType == true){
				$scope.dateErr1 = 0;
		    	}else if(dateType == false){
				$scope.dateErr1	 = 1;
		    	}
			if($scope.dt2 > new Date() || $scope.dt2 < new Date("12/31/1900"))
			  	$scope.dateErr1	 = 1;
		    }
		     

		};
		
		$http.get(aboutusGetUrl).then(function(response){
			  var respData = response.data.data;			  
			  $scope.data_options = response.data.data_options;
			  $scope.account = response.data.data;
			  $scope.userTypes = respData.profile_type;
			  $scope.ethnicity =  $scope.account.profiles[0].ethnicity;
			  var person1_dob = $scope.account.profiles[0].dob;
			  
			  if(typeof(person1_dob)  !== "undefined" && person1_dob != '0000-00-00'){
				  $scope.pDoBext = person1_dob.split("-");
				  if($scope.pDoBext.length == 3){
					$scope.dt = new Date($scope.pDoBext[0],$scope.pDoBext[1] -1,$scope.pDoBext[2]);
				  }
			  }else{
			  	$scope.dt ='';
			  }
			  if(respData.profile_type == 'couple'){
			  		var person2_dob = $scope.account.profiles[1].dob;
			  		if(typeof($scope.account.profiles[1].dob)  !== "undefined"  && person2_dob != '0000-00-00' ){
					  $scope.cDoBExt = $scope.account.profiles[1].dob.split("-");
					  if($scope.cDoBExt.length == 3){
						$scope.dt2 = new Date($scope.cDoBExt[0],$scope.cDoBExt[1] -1,$scope.cDoBExt[2]);
					  }				  
				  	}else{
				  		$scope.dt2 = '';
				  	}

			  }
			  
                          console.log('date new', angular.isDate($scope.dt));
		});

		$scope.submit = function(){
                        console.log('submitted');

			$scope.account.profiles[0].waiting_id = $scope.account.waiting;
			if(typeof($scope.dt) != 'undefined' && $scope.dt != '')
				$scope.account.profiles[0].dob = $scope.dt.getFullYear() + "-" + ($scope.dt.getMonth() + 1) + "-" + $scope.dt.getDate();
			else
				$scope.account.profiles[0].dob = '';
			if($scope.account.profile_type == 'couple' && typeof($scope.dt2) != 'undefined' &&  $scope.dt2 != ''){
				$scope.account.profiles[1].dob = $scope.dt2.getFullYear() + "-" + ($scope.dt2.getMonth() + 1) + "-" + $scope.dt2.getDate();


			}else if(typeof($scope.account.profiles[1]) != "undefined")
				$scope.account.profiles[1].dob = '';
			dhtmlx.message({
                    type: "Loading",
                    text: "Please wait while we are loading your profile details... " 
                });
			var req = {
 				method: 'POST',
 				url: aboutusPostUrl,
 				headers: {
   						'Authorization': 'OAuth2.0',
   						'access_token': access_token
 				},
 				data: { 'access_token' : access_token, 'data' : $scope.account }
				}

			var res = $http(req).then(function(response){
				if(response.data.code == "200"){
					dhtmlx.alert("Changes saved. Your profile has been successfully updated", function (result) {
                   
                	})

				}
				
			})
		}

}]);

app.controller('contactusCntrl',['$scope','$http',function($scope,$http,$window){
	$http.get(contactUsGetUrl).then(function(response){
			  var respData = response.data.data;
			  if(respData.StreetAddress !== ''){
                          	var add = respData.StreetAddress.split(',');
           		  	respData.address1 = add[0].trim();
            		  	respData.address2 = add[1].trim();
			  }
			  $scope.data_options = response.data.data_options;
			  $scope.account = respData; //submitContactInfo
              if ($scope.account.DefaultContact == 1)
                $scope.account.DefaultContacts_form = true;
            else
                $scope.account.DefaultContacts_form = false;
                          $scope.submit = function(){
						  if ($scope.account.DefaultContacts_form == false)
                    $scope.account.DefaultContact = 0;
                else
                    $scope.account.DefaultContact = 1;
		    $scope.StreetAddress = '';
		    if($scope.address1 !== '' && typeof($scope.address1) !== 'undefined')
                          $scope.StreetAddress = $scope.address1;
		    if($scope.address2 !== '' && typeof($scope.address2) !== 'undefined')
			  $scope.StreetAddress = $scope.StreetAddress + ', ' + $scope.address2;
			var req = {
 				method: 'POST',
 				url: contactusPostUrl,
 				headers: {
   						'Authorization': 'OAuth2.0',
   						'access_token': access_token
 				},
 				data: { 'access_token' : access_token, 'data' : $scope.account }
				}

			var res = $http(req).then(function(response){
                     if(response.data.code == "200"){
					dhtmlx.alert("Changes saved. Your profile has been successfully updated", function (result) {
                   
                	})

				}

			})
		}
 $scope.getStates = function () {
                
                var req = {
                    method: 'POST',
                    url: getStatesUrl,
                    headers: {
                        'Authorization': 'OAuth2.0',
                        'access_token': access_token
                    },
                    data: {'access_token': access_token, 'data': $scope.account}
                }
                $scope.account.State = '';
                var res = $http(req).then(function (response) {
                    console.log(response.data.data_options);
                    var respData = response.data.data_options;
                    
                    
                    //$scope.data_option = respData.states;
                    $scope.data_options.states = respData.states;
                    
                    
                    
                });
            }


	});	
}]);

app.controller('childPrefCntrl',['$scope','$http',function($scope,$http,$window){

	//get form data fields
	var currentElement = angular.element('#child_desired');
      	currentElement.multiselect({
        	includeSelectAllOption: true,
        	 enableFiltering: true,
        selectAllName: 'select-all-name',
        selectAllNumber: false,

      });
	$http.get(childPrefGetUrl).then(function(response){
			  var respData = response.data.data;
			  $scope.data_options = response.data.data_options;
			  $scope.account = respData;
			  $scope.getSelect = function(text){
                              console.log('text', text);
                                console.log($scope.account.adoption_type_pref);
                                if( $scope.account.child_desired == '-1001' || $scope.account.age_group_pref== '-1001'  || $scope.account.ethnicityprefs== '-1001' || $scope.account.adoption_type_pref == '-1001' || $scope.account.birthfatherPrefs == '-1001'){
                                  if(text == 'child_desired' && $scope.account.child_desired == '-1001'){ 
                                      console.log('p');
                                  $scope.account.child_desired= []; 
                                    angular.forEach($scope.data_options.child_desired, function(item){
                                            if(item.selectVal == '-1001'){
                                                item.selectVal = '-1002';
                                                item.selectText = 'None';
                                            }
                                                
                                            $scope.account.child_desired.push( item.selectVal);
                                     });
                                 }else if(text == 'age_group' && $scope.account.age_group_pref== '-1001'){
                                     console.log('fffff');
                                     $scope.account.age_group_pref= []; 
                                     angular.forEach($scope.data_options.age_group, function(item){
                                            if(item.selectVal == '-1001'){
                                                item.selectVal = '-1002';
                                                item.selectText = 'None';
                                            }
                                                
                                            $scope.account.age_group_pref.push( item.selectVal);
                                     });
                                 }else if(text == 'ethnicity' && $scope.account.ethnicityprefs== '-1001'){
                                     console.log('hh');
                                     $scope.account.ethnicityprefs= []; 
                                     angular.forEach($scope.data_options.ethnicity, function(item){
                                            if(item.selectVal == '-1001'){
                                                item.selectVal = '-1002';
                                                item.selectText = 'None';
                                            }
                                                
                                            $scope.account.ethnicityprefs.push( item.selectVal);
                                     });
                                 }
                                 else if((text == 'adoption_type') && ($scope.account.adoption_type_pref== '-1001')){
                                     console.log('1');
                                     $scope.account.adoption_type_pref = [];
                                     console.log('2');
                                     angular.forEach($scope.data_options.adoption_type, function(item){
                                         console.log('3');
                                            if(item.selectVal == '-1001'){
                                                item.selectVal = '-1002';
                                                item.selectText = 'None';
                                            }
                                                
                                            $scope.account.adoption_type_pref.push( item.selectVal);
                                     });
                                 }else if(text == 'birthfather_status' && $scope.account.birthfatherPrefs== '-1001'){
                                     console.log('55');
                                     $scope.account.birthfatherPrefs= []; 
                                     angular.forEach($scope.data_options.birthfather_status, function(item){
                                            if(item.selectVal == '-1001'){
                                                item.selectVal = '-1002';
                                                item.selectText = 'None';
                                            }
                                                
                                            $scope.account.birthfatherPrefs.push( item.selectVal);
                                     });
                                 }else{
                                  console.log('hhhh');
                                 }
                                }
                                else if( $scope.account.child_desired == '-1002' || $scope.account.age_group_pref== '-1002' || $scope.account.ethnicityprefs== '-1002' || $scope.account.adoption_type_pref== '-1002' || $scope.account.birthfatherPrefs == '-1002'){
                                    if(text == 'child_desired' && $scope.account.child_desired == '-1002'){ 
                                        $scope.account.child_desired = [];
                                        $scope.data_options.child_desired[0].selectVal = '-1001';
                                        $scope.data_options.child_desired[0].selectText = 'All';
                                    }else if(text == 'age_group' && $scope.account.age_group== '-1002'){
                                        $scope.account.age_group_pref = [];
                                        $scope.data_options.age_group[0].selectVal = '-1001';
                                        $scope.data_options.age_group[0].selectText = 'All';
                                    }else if(text == 'ethnicity' && $scope.account.ethnicity== '-1002'){
                                        $scope.account.ethnicityprefs = [];
                                        $scope.data_options.ethnicity[0].selectVal = '-1001';
                                        $scope.data_options.ethnicity[0].selectText = 'All';
                                    }else if(text == 'adoption_type' && $scope.account.adoption_type_pref == '-1002'){
                                        $scope.account.adoption_type_pref = [];
                                        $scope.data_options.adoption_type[0].selectVal = '-1001';
                                        $scope.data_options.adoption_type[0].selectText = 'All';
                                    }else if(text == 'birthfather_status' && $scope.account.birthfatherPrefs == '-1002'){
                                        $scope.account.birthfatherPrefs = [];
                                        $scope.data_options.birthfather_status[0].selectVal = '-1001';
                                        $scope.data_options.birthfather_status[0].selectText = 'All';
                                    }else{
                                        
                                    }
                                }else{
                                    if(text == 'child_desired'){ 
                                        $scope.data_options.child_desired[0].selectVal = '-1001';
                                        $scope.data_options.child_desired[0].selectText = 'All';
                                    }else if(text == 'age_group' ){
                                        $scope.data_options.age_group[0].selectVal = '-1001';
                                        $scope.data_options.age_group[0].selectText = 'All';
                                    }else if(text == 'ethnicity'){
                                        $scope.data_options.ethnicity[0].selectVal = '-1001';
                                        $scope.data_options.ethnicity[0].selectText = 'All';
                                    }else if(text == 'adoption_type'){
                                        $scope.data_options.adoption_type[0].selectVal = '-1001';
                                        $scope.data_options.adoption_type[0].selectText = 'All';
                                    }else if(text == 'birthfather_status'){
                                        $scope.data_options.birthfather_status[0].selectVal = '-1001';
                                        $scope.data_options.birthfather_status[0].selectText = 'All';
                                    }else{
                                        
                                    }
                                }
			  }	


	});	
   


	//write function to save the form field values
	$scope.saveChildPrefData = function(){
		console.log($scope.account);
		dhtmlx.message({
                type: "Loading",
                text: "Please wait while we are loading your profile details... " 
            });
		var req = {
			method: 'POST',
			url: childPrefPostUrl,
			headers: {
					'Authorization': 'OAuth2.0',
					'access_token': access_token
			},
			data: { 'access_token' : access_token, 'data' : $scope.account }
		};
		var res = $http(req).then(function(response){
			if(response.data.code == "200"){
				dhtmlx.alert("Changes saved. Your profile has been successfully updated", function (result) {
               
            	})

			}
			
		});
	}


}]);