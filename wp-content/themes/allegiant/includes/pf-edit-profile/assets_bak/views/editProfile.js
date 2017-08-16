'use strict';

var api_url = '';
var access_token = '';
//console.log(access_token);
var aboutusGetUrl = api_url + "v1/editprofile/aboutus?access_token=" + access_token;
var contactUsGetUrl = api_url + "v1/editprofile/contactus?access_token=" + access_token;
var childPrefGetUrl = api_url + "v1/editprofile/childpreference?access_token=" + access_token;
var aboutusPostUrl = api_url + "v1/editprofile/save/aboutus";
var contactusPostUrl = api_url + "v1/editprofile/save/contactus";
var childPrefPostUrl = api_url + "v1/editprofile/save/childpreference";
var getStatesUrl = api_url + "v1/editprofile/get/states";

var app = angular.module('ui.bootstrap.demo', ['ngAnimate', 'ngSanitize', 'ngRoute', 'ui.bootstrap', 'ui.mask', 'internationalPhoneNumber']);
app.config(['$qProvider', function ($qProvider) {
        $qProvider.errorOnUnhandledRejections(false);
    }]);
app.controller('TabsCtrl', ['$scope', '$http', '$location', function ($scope, $http, $window, $location) {
        $scope.formStatus = false;
        $scope.show = false;
        $scope.saved = false;
        $scope.model = {
            name: 'Tabs'
        };
        $scope.$watch('access_token', function () {
            access_token = $scope.access_token;

        });
        $scope.$watch('site_url', function () {
            api_url = $scope.site_url;
            aboutusGetUrl = api_url + "v1/editprofile/aboutus?access_token=" + access_token;
            contactUsGetUrl = api_url + "v1/editprofile/contactus?access_token=" + access_token;
            childPrefGetUrl = api_url + "v1/editprofile/childpreference?access_token=" + access_token;
            aboutusPostUrl = api_url + "v1/editprofile/save/aboutus";
            contactusPostUrl = api_url + "v1/editprofile/save/contactus";
            getStatesUrl = api_url + "v1/editprofile/get/states"
            childPrefPostUrl = api_url + "v1/editprofile/save/childpreference";
        });

        $scope.model.tabs = [
            {
                title: "About Us",
                templateUrl: 'assets/views/about_us.html',
                isLoaded: false
            },
            {
                title: "Child Preferences",
                templateUrl: 'assets/views/about_child.html',
                isLoaded: false
            },
            {
                title: "Contact Info",
                templateUrl: 'assets/views/contact_info.html',
                isLoaded: false
            }



        ];
        //show unsaved Data and show saved successfully message
        $scope.newFn = function (status) {
            console.log('--',status);
            $scope.formStatus = status;
            $scope.show = false;
            $scope.saved = false;
            if ($scope.formStatus === true) {
                $scope.formStatus = status;
                $scope.show = true;
            }
            else if ($scope.formStatus === 2) {
                $scope.show = false;
                $scope.saved = true;
            } else {

            }
        };
        //$scope.tabContent = $scope.model.tabs[1].templateUrl;
        $scope.model.tabs[0].isLoaded = true;
        $scope.onTabdeselect = function (index, $event) {

            console.log('index', index);
            if ($scope.formStatus === true) {
                $event.preventDefault();
                $scope.show = true;
            } else {
		$scope.saved = false;
		$scope.show = false;
                $scope.model.tabs[index].isLoaded = true;
            }
        }
        $scope.onTabSelected = function (index) {
            $scope.show = false;
            $scope.model.tabs[index].isLoaded = true;

        };


    }]);

app.controller('aboutUsController', ['$scope', '$http', function ($scope, $http, $window) {
        //$qProvider.errorOnUnhandledRejections(false);
        $scope.dt = new Date();
        $scope.dt2 = new Date();
        $scope.open1 = function () {
            $scope.popup1.opened = true;
        };
        $scope.popup1 = {
            opened: false
        };
        $scope.open2 = function () {
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
        $scope.validateDate = function () {
	    if($scope.aboutus.calender1.$invalid)
		$scope.dt = '';
	    if($scope.aboutus.calender2.$invalid)
		$scope.dt2 = '';

            if ($scope.dt != '') {
                var dateType = angular.isDate($scope.dt);
                console.log('ggg',dateType);
                if (dateType == true) {
                    $scope.dateErr = 0;
                } else if (dateType == false) {
                    $scope.dateErr = 1;
                }
                if ($scope.dt > new Date() || $scope.dt < new Date("12/31/1900")){
                    $scope.dateErr = 1;
                    console.log('ff',$scope.dateErr);
                }
            }
            if ($scope.dt2 != '') {
                var dateType = angular.isDate($scope.dt2);
                console.log('ggg1',dateType);
                if (dateType == true) {
                    $scope.dateErr1 = 0;
                } else if (dateType == false) {
                    $scope.dateErr1 = 1;
                }
                if ($scope.dt2 > new Date() || $scope.dt2 < new Date("12/31/1900")){
                    $scope.dateErr1 = 1;
                    console.log('pp',$scope.dateErr1);
                }
                 console.log('pp1',$scope.dateErr1);
            }


        };

        $http.get(aboutusGetUrl).then(function (response) {
            var respData = response.data.data;
            $scope.data_options = response.data.data_options;
            $scope.account = response.data.data;
            $scope.userTypes = respData.profile_type;
            $scope.ethnicity = $scope.account.profiles[0].ethnicity;
            var person1_dob = $scope.account.profiles[0].dob;

            if (typeof (person1_dob) !== "undefined" && person1_dob != '0000-00-00') {
                $scope.pDoBext = person1_dob.split("-");
                if ($scope.pDoBext.length == 3) {
                    $scope.dt = new Date($scope.pDoBext[0], $scope.pDoBext[1] - 1, $scope.pDoBext[2]);
                }
            } else {
                $scope.dt = '';
            }
            if (respData.profile_type == 'couple') {
                var person2_dob = $scope.account.profiles[1].dob;
                if (typeof ($scope.account.profiles[1].dob) !== "undefined" && person2_dob != '0000-00-00') {
                    $scope.cDoBExt = $scope.account.profiles[1].dob.split("-");
                    if ($scope.cDoBExt.length == 3) {
                        $scope.dt2 = new Date($scope.cDoBExt[0], $scope.cDoBExt[1] - 1, $scope.cDoBExt[2]);
                    }
                } else {
                    $scope.dt2 = '';
                }

            }
        });

        $scope.submit = function () {
            //$scope.$parent.newFn(false);
            var myEl = angular.element( document.querySelector( '#cancel2' ) );
            myEl.removeClass('cancel_btn');
            myEl.addClass('cancel_btn_disabled');
            var myE2 = angular.element( document.querySelector( '#save2' ) );
            myE2.removeClass('save_btn');
            myE2.addClass('save_btn_disabled');
            var myEl = angular.element( document.querySelector( '#cancel_bottom2' ) );
            myEl.removeClass('cancel_btn');
            myEl.addClass('cancel_btn_disabled');
            var myE2 = angular.element( document.querySelector( '#save_bottom2' ) );
            myE2.removeClass('save_btn');
            myE2.addClass('save_btn_disabled');
            $scope.disabled = true;
            if($scope.dateErr == 1){
                $scope.err1 = 1;
                //return false;
            }else if($scope.dateErr == 0){
                $scope.err1 = 0;
            }else if($scope.dateErr1 == 1){
                $scope.err2 = 1;
                //return false;
            }else if($scope.dateErr1 == 0){
                $scope.err2 = 0;
            }else{
                
            }
            console.log('g',$scope.dt);
	    $scope.$parent.formStatus = false;
	    $scope.aboutus.$setPristine();
            $scope.account.profiles[0].waiting_id = $scope.account.waiting;
            if (typeof ($scope.dt) != 'undefined' && $scope.dt != '' && $scope.dt != null)
                $scope.account.profiles[0].dob = $scope.dt.getFullYear() + "-" + ($scope.dt.getMonth() + 1) + "-" + $scope.dt.getDate();
            else
                $scope.account.profiles[0].dob = '';
            if ($scope.account.profile_type == 'couple' && typeof ($scope.dt2) != 'undefined' && $scope.dt2 != '' && $scope.dt2 != null) {
                $scope.account.profiles[1].dob = $scope.dt2.getFullYear() + "-" + ($scope.dt2.getMonth() + 1) + "-" + $scope.dt2.getDate();


            } else if (typeof ($scope.account.profiles[1]) != "undefined")
                $scope.account.profiles[1].dob = '';

            var req = {
                method: 'POST',
                url: aboutusPostUrl,
                headers: {
                    'Authorization': 'OAuth2.0',
                    'access_token': access_token
                },
                data: {'access_token': access_token, 'data': $scope.account}
            }

            var res = $http(req).then(function (response) {
                if (response.data.code == "200") {
//                    dhtmlx.alert("Changes saved. Your profile has been successfully updated", function (result) {
//
//                    })
                    $scope.$parent.newFn(2);
                    $scope.disabled = false;
                }

            })
        };
        $scope.urlValidation = function(){
            var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
if (!re.test($scope.account.website)) { 
    console.log('1', re.test($scope.account.website));
    alert("url error");
    return false;
}        };


    }]);

app.controller('contactusCntrl', ['$scope', '$http', function ($scope, $http, $window) {
        $http.get(contactUsGetUrl).then(function (response) {
            var respData = response.data.data;
            if (respData.StreetAddress !== '') {
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
            console.log('jjj',$scope.account.State);
            if ($scope.account.State == "0"){
                $scope.stateShow = true;
                //$scope.account.State = '';
            }
            //}
            $scope.getStates = function () {
		$scope.disable = true;
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
		     $scope.disable = false;
                     $scope.stateShow = true;
                    //$scope.data_option = respData.states;
                    $scope.data_options.states = respData.states;



                });
            }


        });
        $scope.stateChange = function(){
             $scope.stateShow = false;
        };
        $scope.keyUpfn = function(){
            $scope.$parent.formStatus = true;
            $scope.$parent.show = true;
        };
        $scope.submit = function () {
            //$scope.$parent.newFn(false);
            $scope.disabled = true;
            var myEl = angular.element( document.querySelector( '#cancel' ) );
            myEl.removeClass('cancel_btn');
            myEl.addClass('cancel_btn_disabled');
            var myE2 = angular.element( document.querySelector( '#save' ) );
            myE2.removeClass('save_btn');
            myE2.addClass('save_btn_disabled');
            var myEl = angular.element( document.querySelector( '#cancel_bottom' ) );
            myEl.removeClass('cancel_btn');
            myEl.addClass('cancel_btn_disabled');
            var myE2 = angular.element( document.querySelector( '#save_bottom' ) );
            myE2.removeClass('save_btn');
            myE2.addClass('save_btn_disabled');
            $scope.$parent.formStatus = false;
	    $scope.contactinfo.$setPristine();
            if ($scope.account.DefaultContacts_form == false)
                $scope.account.DefaultContact = 0;
            else
                $scope.account.DefaultContact = 1;
            $scope.StreetAddress = '';
            if ($scope.address1 !== '' && typeof ($scope.address1) !== 'undefined')
                $scope.StreetAddress = $scope.address1;
            if ($scope.address2 !== '' && typeof ($scope.address2) !== 'undefined')
                $scope.StreetAddress = $scope.StreetAddress + ', ' + $scope.address2;
                $scope.account.mobile_num = $scope.contactinfo.phone.$viewValue;
                console.log($scope.account);
            var req = {
                method: 'POST',
                url: contactusPostUrl,
                headers: {
                    'Authorization': 'OAuth2.0',
                    'access_token': access_token
                },
                data: {'access_token': access_token, 'data': $scope.account}
            }
            return false;
            var res = $http(req).then(function (response) {
                if (response.data.code == "200") {
//                        dhtmlx.alert("Changes saved. Your profile has been successfully updated", function (result) {
//
//                        })
                    $scope.$parent.newFn(2);
                    $scope.disabled = false;

                }

            })
        };

    }]);

app.controller('childPrefCntrl', ['$scope', '$http', function ($scope, $http, $window) {
        $http.get(childPrefGetUrl).then(function (response) {
            $scope.$on('$locationChangeStart', function (event) {
                var answer = confirm("Are you sure you want to leave this page?")
                if (!answer) {
                    event.preventDefault();
                }
            });
            var respData = response.data.data;
            $scope.data_options = response.data.data_options;
            $scope.account = respData;
            $scope.getSelect = function (text) {
                if ($scope.account.child_desired == '-1001' || $scope.account.age_group_pref == '-1001' || $scope.account.ethnicityprefs == '-1001' || $scope.account.adoption_type_pref == '-1001' || $scope.account.birthfatherPrefs == '-1001') {
                    if (text == 'child_desired' && $scope.account.child_desired == '-1001') {
                        $scope.account.child_desired = [];
                        angular.forEach($scope.data_options.child_desired, function (item) {
                            if (item.selectVal == '-1001') {
                                item.selectVal = '-1002';
                                item.selectText = 'None';
                            }

                            $scope.account.child_desired.push(item.selectVal);
                        });
                    } else if (text == 'age_group' && $scope.account.age_group_pref == '-1001') {
                        $scope.account.age_group_pref = [];
                        angular.forEach($scope.data_options.age_group, function (item) {
                            if (item.selectVal == '-1001') {
                                item.selectVal = '-1002';
                                item.selectText = 'None';
                            }

                            $scope.account.age_group_pref.push(item.selectVal);
                        });
                    } else if (text == 'ethnicity' && $scope.account.ethnicityprefs == '-1001') {
                        console.log('hh');
                        $scope.account.ethnicityprefs = [];
                        angular.forEach($scope.data_options.ethnicity, function (item) {
                            if (item.selectVal == '-1001') {
                                item.selectVal = '-1002';
                                item.selectText = 'None';
                            }

                            $scope.account.ethnicityprefs.push(item.selectVal);
                        });
                    }
                    else if ((text == 'adoption_type') && ($scope.account.adoption_type_pref == '-1001')) {
                        console.log('1');
                        $scope.account.adoption_type_pref = [];
                        console.log('2');
                        angular.forEach($scope.data_options.adoption_type, function (item) {
                            console.log('3');
                            if (item.selectVal == '-1001') {
                                item.selectVal = '-1002';
                                item.selectText = 'None';
                            }

                            $scope.account.adoption_type_pref.push(item.selectVal);
                        });
                    } else if (text == 'birthfather_status' && $scope.account.birthfatherPrefs == '-1001') {
                        console.log('55');
                        $scope.account.birthfatherPrefs = [];
                        angular.forEach($scope.data_options.birthfather_status, function (item) {
                            if (item.selectVal == '-1001') {
                                item.selectVal = '-1002';
                                item.selectText = 'None';
                            }

                            $scope.account.birthfatherPrefs.push(item.selectVal);
                        });
                    } else {
                    }
                }
                else if ($scope.account.child_desired == '-1002' || $scope.account.age_group_pref == '-1002' || $scope.account.ethnicityprefs == '-1002' || $scope.account.adoption_type_pref == '-1002' || $scope.account.birthfatherPrefs == '-1002') {
                    if (text == 'child_desired' && $scope.account.child_desired == '-1002') {
                        $scope.account.child_desired = [];
                        $scope.data_options.child_desired[0].selectVal = '-1001';
                        $scope.data_options.child_desired[0].selectText = 'All';
                    } else if (text == 'age_group' && $scope.account.age_group_pref == '-1002') {
                        $scope.account.age_group_pref = [];
                        $scope.data_options.age_group[0].selectVal = '-1001';
                        $scope.data_options.age_group[0].selectText = 'All';
                    } else if (text == 'ethnicity' && $scope.account.ethnicityprefs == '-1002') {
                        $scope.account.ethnicityprefs = [];
                        $scope.data_options.ethnicity[0].selectVal = '-1001';
                        $scope.data_options.ethnicity[0].selectText = 'All';
                    } else if (text == 'adoption_type' && $scope.account.adoption_type_pref == '-1002') {
                        $scope.account.adoption_type_pref = [];
                        $scope.data_options.adoption_type[0].selectVal = '-1001';
                        $scope.data_options.adoption_type[0].selectText = 'All';
                    } else if (text == 'birthfather_status' && $scope.account.birthfatherPrefs == '-1002') {
                        $scope.account.birthfatherPrefs = [];
                        $scope.data_options.birthfather_status[0].selectVal = '-1001';
                        $scope.data_options.birthfather_status[0].selectText = 'All';
                    } else {

                    }
                } else {
                    if (text == 'child_desired') {
                        $scope.data_options.child_desired[0].selectVal = '-1001';
                        $scope.data_options.child_desired[0].selectText = 'All';
                    } else if (text == 'age_group') {
                        $scope.data_options.age_group[0].selectVal = '-1001';
                        $scope.data_options.age_group[0].selectText = 'All';
                    } else if (text == 'ethnicity') {
                        $scope.data_options.ethnicity[0].selectVal = '-1001';
                        $scope.data_options.ethnicity[0].selectText = 'All';
                    } else if (text == 'adoption_type') {
                        $scope.data_options.adoption_type[0].selectVal = '-1001';
                        $scope.data_options.adoption_type[0].selectText = 'All';
                    } else if (text == 'birthfather_status') {
                        $scope.data_options.birthfather_status[0].selectVal = '-1001';
                        $scope.data_options.birthfather_status[0].selectText = 'All';
                    } else {

                    }
                }
            }


        });
        //write function to save the form field values
        $scope.submit = function () {
            $scope.disabled = true;
            var myEl = angular.element( document.querySelector( '#cancel1' ) );
            myEl.removeClass('cancel_btn');
            myEl.addClass('cancel_btn_disabled');
            var myE2 = angular.element( document.querySelector( '#save1' ) );
            myE2.removeClass('save_btn');
            myE2.addClass('save_btn_disabled');
            var myEl = angular.element( document.querySelector( '#cancel_bottom1' ) );
            myEl.removeClass('cancel_btn');
            myEl.addClass('cancel_btn_disabled');
            var myE2 = angular.element( document.querySelector( '#save_bottom1' ) );
            myE2.removeClass('save_btn');
            myE2.addClass('save_btn_disabled');
            $scope.$parent.formStatus = false;
	    $scope.aboutchild.$setPristine();
            //$scope.$parent.newFn(false);
            var req = {
                method: 'POST',
                url: childPrefPostUrl,
                headers: {
                    'Authorization': 'OAuth2.0',
                    'access_token': access_token
                },
                data: {'access_token': access_token, 'data': $scope.account}
            };
            var res = $http(req).then(function (response) {
                if (response.data.code == "200") {
//                    dhtmlx.alert("Changes saved. Your profile has been successfully updated", function (result) {
//
//                    })
                    $scope.$parent.newFn(2);
                    $scope.disabled = false;
                }

            });
        }


    }]);