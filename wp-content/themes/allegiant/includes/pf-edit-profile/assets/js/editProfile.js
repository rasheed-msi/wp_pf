//'use strict';

angular.module("uib/template/tabs/tabset.html", []).run(["$templateCache", function($templateCache) {
        $templateCache.put("uib/template/tabs/tabset.html",
                "<div class=\"container\">\n" +
                "<div class=\"row\">\n" +
                "  <div class=\"dashboardTabMenu pull-left\">\n" +
                "  <ul class=\"nav nav-{{tabset.type || 'tabs'}}\" ng-class=\"{'nav-stacked': vertical, 'nav-justified': justified}\" ng-transclude></ul>\n" +
                "    </div>\n" +
                "  <div class=\"dashboardTabsContent flexFullChild pull-left\">\n" +
                "  <div class=\"tab-content\">\n" +
                "    <div class=\"tab-pane\"\n" +
                "         ng-repeat=\"tab in tabset.tabs\"\n" +
                "         ng-class=\"{active: tabset.active === tab.index}\"\n" +
                "         uib-tab-content-transclude=\"tab\">\n" +
                "    </div>\n" +
                "  </div>\n" +
                "  </div>\n" +
                "</div>\n" +
                "</div>\n" +
                "");
    }]);

if (typeof edit_obj != 'undefined') {

    var api_url = '';
    var access_token = '';

    var aboutusGetUrl = edit_obj.aboutus_geturl + "/" + edit_obj.userId;
    var contactUsGetUrl = edit_obj.contactus_geturl + "/" + edit_obj.userId;
    var childPrefGetUrl = edit_obj.childpref_geturl + "/" + edit_obj.userId;
    var aboutusPostUrl = edit_obj.aboutus_posturl;
    var contactusPostUrl = edit_obj.contactus_posturl;
    var childPrefPostUrl = edit_obj.childpref_posturl;
    var getStatesUrl = edit_obj.getstates_url;
    var template_root_path = edit_obj.template_root_path;
    var agencySelectionGetUrl = edit_obj.agencyselection_geturl + "/" + edit_obj.userId;
    var agencySelectionPostUrl = edit_obj.agencyselection_posturl;
    var agencyDelPostUrl = edit_obj.agencydel_posturl;

    angular.module("template/tabs/tabset.html", []).run(["$templateCache", function($templateCache) {
            $templateCache.put("template/tabs/tabset.html",
                    "<div>\n" +
                    "  <ul class=\"nav nav-{{type || 'tabs'}}\" ng-class=\"{'nav-stacked': vertical, 'nav-justified': justified}\" ng-transclude></ul>\n" +
                    "  <div class=\"tab-content vfsdgfdgdfg\">\n" +
                    "    <div class=\"tab-pane\" \n" +
                    "         ng-repeat=\"tab in tabs\" \n" +
                    "         ng-class=\"{active: tab.active}\"\n" +
                    "         uib-tab-content-transclude=\"tab\">\n" +
                    "    </div>\n" +
                    "  </div>\n" +
                    "</div>\n" +
                    "");
        }]);
    var app = angular.module('ui.bootstrap.demo', ['ngAnimate', 'ngSanitize', 'ngRoute', 'ui.bootstrap', 'ui.mask']);

    app.config(['$qProvider', function($qProvider) {
            $qProvider.errorOnUnhandledRejections(false);
        }]);
    app.controller('TabsCtrl', ['$scope', '$http', '$location', function($scope, $http, $window, $location) {
            $scope.formStatus = false;
            $scope.show = false;
            $scope.saved = false;
            $scope.model = {
                name: 'Tabs'
            };
            $scope.$watch('access_token', function() {
                access_token = $scope.access_token;

            });
            $scope.$watch('site_url', function() {
                api_url = $scope.site_url;

                var aboutusGetUrl = edit_obj.aboutus_geturl + "/" + edit_obj.userId;
                var contactUsGetUrl = edit_obj.contactus_geturl + "/" + edit_obj.userId;
                var childPrefGetUrl = edit_obj.childpref_geturl + "/" + edit_obj.userId;
                var aboutusPostUrl = edit_obj.aboutus_posturl;
                var contactusPostUrl = edit_obj.contactus_posturl;
                var childPrefPostUrl = edit_obj.childpref_posturl;
                var getStatesUrl = edit_obj.getstates_url;
                var template_root_path = edit_obj.template_root_path;
                var agencySelectionGetUrl = edit_obj.agencyselection_geturl + "/" + edit_obj.userId;
                var agencySelectionPostUrl = edit_obj.agencyselection_posturl;
                var agencyDelPostUrl = edit_obj.agencydel_posturl;
            });

            $scope.model.tabs = [
                {
                    title: "About Us",
                    templateUrl: template_root_path + 'assets/views/about_us.html',
                    isLoaded: false
                },
                {
                    title: "Child Preferences",
                    templateUrl: template_root_path + 'assets/views/about_child.html',
                    isLoaded: false
                },
                {
                    title: "Contact Info",
                    templateUrl: template_root_path + 'assets/views/contact_info.html',
                    isLoaded: false
                },
                {
                    title: "Agency Selection",
                    templateUrl: template_root_path + 'assets/views/agency_selection.html',
                    isLoaded: false
                }
            ];
            //show unsaved Data and show saved successfully message
            $scope.newFn = function(status) {
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
            $scope.onTabdeselect = function(index, $event) {

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
            $scope.onTabSelected = function(index) {
                $scope.show = false;
                $scope.model.tabs[index].isLoaded = true;
            };

        }]);

    app.controller('aboutUsController', ['$scope', '$http', function($scope, $http, $window) {
            $scope.masterAbout = {};
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
            $scope.validateDate = function() {
                if ($scope.aboutus.calender1.$invalid)
                    $scope.dt = '';
                if ($scope.aboutus.calender2.$invalid)
                    $scope.dt2 = '';

                if ($scope.dt != '') {
                    var dateType = angular.isDate($scope.dt);
                    if (dateType == true) {
                        $scope.dateErr = 0;
                    } else if (dateType == false) {
                        $scope.dateErr = 1;
                    }
                    if ($scope.dt > new Date() || $scope.dt < new Date("12/31/1900")) {
                        $scope.dateErr = 1;
                        console.log('ff', $scope.dateErr);
                    }
                }
                if ($scope.dt2 != '') {
                    var dateType = angular.isDate($scope.dt2);
                    if (dateType == true) {
                        $scope.dateErr1 = 0;
                    } else if (dateType == false) {
                        $scope.dateErr1 = 1;
                    }
                    if ($scope.dt2 > new Date() || $scope.dt2 < new Date("12/31/1900")) {
                        $scope.dateErr1 = 1;
                        //console.log('pp',$scope.dateErr1);
                    }
                    //console.log('pp1',$scope.dateErr1);
                }


            };

            $http.get(aboutusGetUrl).then(function(response) {
                var respData = response.data.data;
                $scope.data_options = response.data.data_options;
                $scope.account = response.data.data;
                var person1_dob = $scope.account.profiles[0].dob, person2_dob = $scope.account.profiles[1].dob;

                if (typeof (person1_dob) !== "undefined" && person1_dob != '0000-00-00' && person1_dob != null) {
                    $scope.pDoBext = person1_dob.split("-");
                    if ($scope.pDoBext.length == 3) {
                        $scope.dt = new Date($scope.pDoBext[0], $scope.pDoBext[1] - 1, $scope.pDoBext[2]);
                    }
                } else {
                    $scope.dt = '';
                }

                if (typeof (person2_dob) !== "undefined" && person2_dob != '0000-00-00' && person2_dob != null) {
                    $scope.pDoBext = person2_dob.split("-");
                    if ($scope.pDoBext.length == 3) {
                        $scope.dt2 = new Date($scope.pDoBext[0], $scope.pDoBext[1] - 1, $scope.pDoBext[2]);
                    }
                } else {
                    $scope.dt2 = '';
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

                var account = $scope.account, dt = $scope.dt, dt2 = $scope.dt2;
                $scope.masterAbout = {dt: dt, dt2: dt2, account: account};
                console.log($scope.masterAbout, 'get');
//                console.log($scope, "about controller");
            });

            $scope.resetAbout = function() {
                console.log($scope.masterAbout);
                $scope.aboutus.$setPristine();
                $scope.dt = $scope.masterAbout.dt;
                $scope.dt2 = $scope.masterAbout.dt2;
                $scope.account = angular.copy($scope.masterAbout.account);
            }

            $scope.submit = function() {
                //$scope.$parent.newFn(false);
                var myEl = angular.element(document.querySelector('#cancel2'));
                myEl.removeClass('cancel_btn');
                myEl.addClass('cancel_btn_disabled');
                var myE2 = angular.element(document.querySelector('#save2'));
                myE2.removeClass('save_btn');
                myE2.addClass('save_btn_disabled');
                var myEl = angular.element(document.querySelector('#cancel_bottom2'));
                myEl.removeClass('cancel_btn');
                myEl.addClass('cancel_btn_disabled');
                var myE2 = angular.element(document.querySelector('#save_bottom2'));
                myE2.removeClass('save_btn');
                myE2.addClass('save_btn_disabled');
                $scope.disabled = true;
                if ($scope.dateErr == 1) {
                    $scope.err1 = 1;
                    //return false;
                } else if ($scope.dateErr == 0) {
                    $scope.err1 = 0;
                } else if ($scope.dateErr1 == 1) {
                    $scope.err2 = 1;
                    //return false;
                } else if ($scope.dateErr1 == 0) {
                    $scope.err2 = 0;
                } else {

                }
                console.log('test', $scope.account.profiles);
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

                var res = $http(req).then(function(response) {
                    if (response.data.code == "200") {
//                    dhtmlx.alert("Changes saved. Your profile has been successfully updated", function (result) {
//
//                    })
                        $scope.$parent.newFn(2);
                        $scope.disabled = false;
                    }

                })
            };
            $scope.urlValidation = function() {
                var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
                if (!re.test($scope.account.website)) {
                    console.log('1', re.test($scope.account.website));
                    alert("url error");
                    return false;
                }
            };


        }]);

    app.controller('contactusCntrl', ['$scope', '$http', function($scope, $http, $window) {
            $http.get(contactUsGetUrl).then(function(response) {
                var respData = response.data.data;
                if (respData.StreetAddress) {
                    var num = respData.StreetAddress.search(",");
                    if (num > 0) {
                        var add = respData.StreetAddress.split(',');
                        respData.address1 = add[0].trim();
                        respData.address2 = add[1].trim();
                    } else {
                        respData.address1 = respData.StreetAddress;
                    }
                }

                $scope.data_options = response.data.data_options;
                $scope.account = respData; //submitContactInfo
                console.log($scope.account);
                if ($scope.account.DefaultContact == 1)
                    $scope.account.DefaultContacts_form = true;
                else
                    $scope.account.DefaultContacts_form = false;

                if ($scope.account.State == '0') {
                    $scope.stateShow = true;
                    $scope.account.State = '';
                }
                //}
                $scope.getStates = function() {
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
                    var res = $http(req).then(function(response) {
                        console.log(response.data.data_options);
                        var respData = response.data.data_options;
                        $scope.disable = false;
                        $scope.stateShow = true;
                        //$scope.data_option = respData.states;
                        $scope.data_options.states = respData.states;



                    });
                }


            });
            $scope.stateChange = function() {
                $scope.stateShow = false;
            };
            $scope.keyUpfn = function() {
                $scope.$parent.formStatus = true;
                $scope.$parent.show = true;
            };
            $scope.submit = function() {
                $scope.disabled = true;
                var myEl = angular.element(document.querySelector('#cancel'));
                myEl.removeClass('cancel_btn');
                myEl.addClass('cancel_btn_disabled');
                var myE2 = angular.element(document.querySelector('#save'));
                myE2.removeClass('save_btn');
                myE2.addClass('save_btn_disabled');
                var myEl = angular.element(document.querySelector('#cancel_bottom'));
                myEl.removeClass('cancel_btn');
                myEl.addClass('cancel_btn_disabled');
                var myE2 = angular.element(document.querySelector('#save_bottom'));
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
//                headers: {
//                    'Authorization': 'OAuth2.0',
//                    'access_token': access_token
//                },
                    data: {'access_token': access_token, 'data': $scope.account}
                }

                var res = $http(req).then(function(response) {
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

    app.controller('childPrefCntrl', ['$scope', '$http', function($scope, $http, $window) {
            $http.get(childPrefGetUrl).then(function(response) {
                $scope.$on('$locationChangeStart', function(event) {
                    var answer = confirm("Are you sure you want to leave this page?")
                    if (!answer) {
                        event.preventDefault();
                    }
                });
                var respData = response.data.data;
                $scope.data_options = response.data.data_options;
                $scope.account = respData;
                $scope.getSelect = function(text) {
                    if ($scope.account.child_desired == '-1001' || $scope.account.age_group_pref == '-1001' || $scope.account.ethnicityprefs == '-1001' || $scope.account.adoption_type_pref == '-1001' || $scope.account.birthfatherPrefs == '-1001') {
                        if (text == 'child_desired' && $scope.account.child_desired == '-1001') {
                            $scope.account.child_desired = [];
                            angular.forEach($scope.data_options.child_desired, function(item) {
                                if (item.selectVal == '-1001') {
                                    item.selectVal = '-1002';
                                    item.selectText = 'None';
                                }

                                $scope.account.child_desired.push(item.selectVal);
                            });
                        } else if (text == 'age_group' && $scope.account.age_group_pref == '-1001') {
                            $scope.account.age_group_pref = [];
                            angular.forEach($scope.data_options.age_group, function(item) {
                                if (item.selectVal == '-1001') {
                                    item.selectVal = '-1002';
                                    item.selectText = 'None';
                                }

                                $scope.account.age_group_pref.push(item.selectVal);
                            });
                        } else if (text == 'ethnicity' && $scope.account.ethnicityprefs == '-1001') {
                            console.log('hh');
                            $scope.account.ethnicityprefs = [];
                            angular.forEach($scope.data_options.ethnicity, function(item) {
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
                            angular.forEach($scope.data_options.adoption_type, function(item) {
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
                            angular.forEach($scope.data_options.birthfather_status, function(item) {
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
            $scope.submit = function() {
                $scope.disabled = true;
                var myEl = angular.element(document.querySelector('#cancel1'));
                myEl.removeClass('cancel_btn');
                myEl.addClass('cancel_btn_disabled');
                var myE2 = angular.element(document.querySelector('#save1'));
                myE2.removeClass('save_btn');
                myE2.addClass('save_btn_disabled');
                var myEl = angular.element(document.querySelector('#cancel_bottom1'));
                myEl.removeClass('cancel_btn');
                myEl.addClass('cancel_btn_disabled');
                var myE2 = angular.element(document.querySelector('#save_bottom1'));
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
                var res = $http(req).then(function(response) {
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

    app.controller('agencySelectionCntrl', ['$scope', '$http', '$uibModal', function($scope, $http, $uibModal, $window) {
            $scope.agencyList = [];
            $scope.selectedAgencies = [];
            $scope.showAgencyBlock = false;
            $scope.resultsHidden = false;
            $scope.userId = 0;
            $http.get(agencySelectionGetUrl).then(function(response) {
                $scope.agencyList = response.data.agencyList;
                $scope.userId = response.data.userId;
                $scope.selectedAgencies = response.data.data_options;
                $scope.showAgencyBlock = true;
            });

            $scope.agencyCheck = function() {
            }

            $scope.addAgencies = function() {
                var agencyList = [];
                angular.forEach($scope.agencyList, function(value, key) {
                    if (typeof value.selected != 'undefined' && value.selected == true) {
                        delete value.selected;
                        $scope.selectedAgencies.push(value);
                    } else {
                        agencyList.push(value);
                    }
                });
                $scope.agencyList = agencyList;
            };

            $scope.deleteAgency = function(selectedAgency) {

                var AgencyName = selectedAgency.agencyName;
                if (typeof $scope.searchAgency !== 'undefined' && $scope.searchAgency.length <= 0) {
                    $scope.resultsHidden = false;
                    $scope.filter = $scope.searchAgency;
                }

                var modalInstance = $uibModal.open({
                    template: '<div class="modal-header"><h3>Remove ' + AgencyName + '?</h3></div>\n\
                            <div class="modal-body"><p>Are you sure you want to remove this agency?</p></div>\n\
                            <div class="modal-footer">\n\
                                <button type="button" class="btn" data-ng-click="cancel()">Cancel</button>\n\
                                <button class="btn btn-primary" data-ng-click="ok();">Remove Agency</button>\n\
                            </div>',
                    controller: 'popupModalCntrl'
                });


                modalInstance.result.then(function(result) {

                    if (selectedAgency.pf_agency_user_id != '') {
                        $http({method: 'POST', url: agencyDelPostUrl, data: {'access_token': access_token,
                                'data': {
                                    agency: selectedAgency,
                                    userId: $scope.userId
                                }
                            }}).then(function(response) {

                        });
                        $scope.agencyList.push(selectedAgency);
                        var index = $scope.selectedAgencies.indexOf(selectedAgency);
                        $scope.selectedAgencies.splice(index, 1);
                    } else {
                        $scope.agencyList.push(selectedAgency);
                        var index = $scope.selectedAgencies.indexOf(selectedAgency);
                        $scope.selectedAgencies.splice(index, 1);
                    }



                });

            };

            $scope.keyUpfn = function() {
                $scope.$parent.formStatus = true;
                $scope.$parent.show = true;
            };
            $scope.submit = function() {

                var req = {
                    method: 'POST',
                    url: agencySelectionPostUrl,
                    headers: {
                        'Authorization': 'OAuth2.0',
                        'access_token': access_token
                    },
                    data: {'access_token': access_token, 'data': {agencyList: $scope.selectedAgencies, userId: $scope.userId}}
                }

                var res = $http(req).then(function(response) {
                    if (response.data.code == "200") {

                        $scope.agencyList = response.data.agencyList;
                        $scope.userId = response.data.userId;
                        $scope.selectedAgencies = response.data.data_options;

                        $scope.$parent.newFn(2);
                        $scope.disabled = false;
                    }
                });
            };
        }]);


    app.controller('popupModalCntrl', ['$scope', '$uibModalInstance', function($scope, $uibModalInstance) {

            $scope.ok = function() {
                $uibModalInstance.close();
            };

            $scope.cancel = function() {
                $uibModalInstance.dismiss('cancel');
            };
        }]);

}