'use strict';
var app = angular.module('ui.bootstrap.demo', ['ngAnimate', 'ngSanitize', 'ngRoute','ui.bootstrap']);
app.controller('TabsCtrl', ['$scope','$http',function ($scope,$http, $window) {
app.directive('editProfileAboutUs',function(){
	return {
    restrict: 'E',
    templateUrl: 'views/about_us.html'
  };
	
}	);

	$scope.oneAtATime = true;
	//$scope.items = [];
	$scope.selected1 = null;
	$scope.today = function() {
		$scope.dt = new Date();
		$scope.dt2 = new Date();
	 };
  $scope.model = {
    name: 'Tabs'
  };
  
	  $scope.model.tabs = [
			{
				title: "About Us",
				templateUrl: 'assets/views/about_us.html'
			},
			{
				title: "Our Home",
				templateUrl: 'assets/views/our_home.html'
			},			
			{
				title: "Child Preferences",
				templateUrl: 'assets/views/about_child.html'
			},
			{
				title: "Contact Info",
				templateUrl: 'assets/views/contact_info.html'
			},
			{
				title: "Social",
				templateUrl: 'assets/views/social.html'
			},			
			{
				title: "Other",
				templateUrl: 'assets/views/others.html'
			}
		]		
		$scope.childrentype = [{
                        text: "--Select--",
                        value: "",
                        selected: true
                    }, {
                        text: "Adopted",
                        value: "Adopted"
                    }, {
                        text: "Biological",
                        value: "Biological"
                    }, {
                        text: "Biological and adopted",
                        value: "Biological and adopted"
                    }, {
                        text: "Foster children",
                        value: "Foster children"
                    }];

		$scope.today();
		$scope.pDoBext = '';
		$scope.cDoBExt = '';
		$scope.special_need = "no";
		//open datepicker popup window
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
		$http.get('assets/processors/getProfile.php').then(function(response){
			  var respData = response.data.data;
			  $scope.ffields = respData;    
			 
			  $scope.letters = respData.letters;
			  $scope.petsSelected = respData.Profiles.Pet.split(",");
			  $scope.faithsSelected = respData.Profiles.faith.split(",");
			  $scope.ethnicity_preference = respData.Profiles.childethnicity.split(",");
			  $scope.desired_age = respData.Profiles.childage.split(",");
			  $scope.special_needs = respData.Profiles.SpecialNeedsOptions.split(",");
			  $scope.childDesired = respData.Profiles.ChildDesired.split(",");
			  $scope.BirthFatherStatus = respData.Profiles.BirthFatherStatus.split(",");
			  $scope.Adoptiontype = respData.Profiles.Adoptiontype.split(",");
			  if(respData.Profiles.SpecialNeedsOptions.length > 0){
			  	$scope.special_need = "yes"
			  }else{
			  	$scope.special_need = "no";	
			  }
			  

			  if(typeof(respData.Profiles.DateOfBirth)  !== "undefined" ){
				  $scope.pDoBext = respData.Profiles.DateOfBirth.split("-");
				  if($scope.pDoBext.length == 3){
					$scope.dt = new Date($scope.pDoBext[0],$scope.pDoBext[1] -1,$scope.pDoBext[2]);
				  }
			  }
			  if(typeof(respData.Couples.DateOfBirth)  !== "undefined" ){
				  $scope.cDoBExt = respData.Couples.DateOfBirth.split("-");
				  if($scope.cDoBExt.length == 3){
					$scope.dt2 = new Date($scope.cDoBExt[0],$scope.cDoBExt[1] -1,$scope.cDoBExt[2]);
				  }				  
			  }
			  

		});


		$scope.submitAboutUs = function(){

				console.log($scope.ffields.Profiles.FirstName);


		}
}]);