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

		$http.get(aboutusGetUrl).then(function(response){
			  var respData = response.data.data;			  
			  $scope.data_options = response.data.data_options;
			  $scope.account = response.data.data;
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
			  		if(typeof($scope.account.profiles[1].dob)  !== "undefined" ){
					  $scope.cDoBExt = $scope.account.profiles[1].dob.split("-");
					  if($scope.cDoBExt.length == 3){
						$scope.dt2 = new Date($scope.cDoBExt[0],$scope.cDoBExt[1] -1,$scope.cDoBExt[2]);
					  }				  
				  	}else{
				  		$scope.dt2 = '';
				  	}

			  }
			  

		});

		$scope.submitAboutUs = function(){

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
                          var add = respData.StreetAddress.split(',');
                          respData.address1 = add[0];
                          respData.address2 = add[1];
			  $scope.data_options = response.data.data_options;
			  $scope.account = respData; //submitContactInfo
                          $scope.submitContactInfo = function(){
                          $scope.StreetAddress = $scope.address1 + ', ' + $scope.address2;
			
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

			})
		}

	});	
}]);

app.controller('childPrefCntrl',['$scope','$http',function($scope,$http,$window){

	//get form data fields
	$http.get(childPrefGetUrl).then(function(response){
			  var respData = response.data.data;
			  $scope.data_options = response.data.data_options;
			  $scope.account = respData;

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