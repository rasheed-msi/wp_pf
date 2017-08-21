<div class="accordianItem">
    <div class="accordianItemHeader clearfix flexbox verticalAlign">
        <a href="<?php echo site_url() . '/your-profile'; ?>" class="buttons text-center"><i class="fa fa-pencil"></i></a>
        <div class="accordianItemIcons clearfix">
            <svg version="1.1" role="presentation" viewBox="0 0 2048 1792" class="fa-icon"><path fill="#ffffff" d="M640 896v512h-256v-512h256zM1024 384v1024h-256v-1024h256zM2048 1536v128h-2048v-1536h128v1408h1920zM1408 640v768h-256v-768h256zM1792 256v1152h-256v-1152h256z"/></svg>
        </div>
        <div class="accordianItemHeaderText">vitals</div>
    </div>
    <div class="accordianItemContents">
        <div class="articles">
            <div class="row articleRow">
                <div class="col-lg-5 col-md-5 articleColumn">
                    <div class="articleItem">
                        <div class="articleItemHead pink">Our adoption preferences</div>
                        <div class="articleItemContents">
                            <div class="articleItemWidget">
                                <h6 class="text-capitalize">Ethnicity:</h6>                                              
                                <p>
                                    <span ng-repeat="pre_ethinicity in preferences.ethnicity">{{pre_ethinicity.ethnicity}}<span ng-if="!$last">,&nbsp;</span></span>
                                </p>
                            </div>
                            <div class="articleItemWidget">
                                <h6 class="text-capitalize">Age:</h6>
                                <p><span ng-repeat="pre_age in preferences.age">{{pre_age.Age_group}}<span ng-if="!$last">,&nbsp;</span></span></p>
                            </div>
                            <div class="articleItemWidget">
                                <h6 class="text-capitalize">Adoption type:</h6>
                                <p><span ng-repeat="pre_type in preferences.type">{{pre_type.adoption_type}}<span ng-if="!$last">,&nbsp;</span></span></p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 articleColumn">
                    <div class="articleItem flexbox">

                        <div class="articleItemColumn flexFullChild">
                            <div class="articleItemHead blue">{{profile.first_name}}</div>
                            <div class="articleItemContents">
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Gender:</h6>
                                    <p>{{info.gender}}</p>
                                </div>
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Ethnicity:</h6>
                                    <p>{{info.ethnicity}}</p>
                                </div>
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Education:</h6>
                                    <p>{{info.education}}</p>
                                </div>
                                <div class="articleItemWidget">
                                    <h6 class="text-capitalize">Religion:</h6>
                                    <p>{{info.religion}}</p>
                                </div>

                            </div>
                        </div>
                        <!--                                    
                        <div class="articleItemColumn flexFullChild">
                                                                <div class="articleItemHead blue">{{profile.spouse_first_name}}</div>
                                                                <div class="articleItemContents">
                                                                    <div class="articleItemWidget">
                                                                        <h6 class="text-capitalize">Gender:</h6>
                                                                        <p>{{info.spouse_gender}}</p>
                                                                    </div>
                                                                    <div class="articleItemWidget">
                                                                        <h6 class="text-capitalize">Ethnicity:</h6>
                                                                        <p>{{info.spouse_ethnicity}}</p>
                                                                    </div>
                                                                    <div class="articleItemWidget">
                                                                        <h6 class="text-capitalize">Education:</h6>
                                                                        <p>{{info.spouse_education}}</p>
                                                                    </div>
                                                                    <div class="articleItemWidget">
                                                                        <h6 class="text-capitalize">Religion:</h6>
                                                                        <p>{{info.spouse_religion}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                        -->


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>