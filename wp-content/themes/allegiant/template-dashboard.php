<?php
/**
 * 
 * Template Name: Dashboard
 */
get_header();
?>
<section class="container" ng-app="appParentfinder">
    <!--Profile Start-->
    <div class="profile" style="margin-top:25px;" ng-controller="dashboardController">

        <div class="profileSection profileIntro clearfix">
            <div class="row profileRow">
                <div class="col-lg-4 col-md-4 profileColumn clearfix">
                    <div class="pull-left profileImage">
                        <img src="{{profile.avatar}}">
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 profileColumn clearfix">
                    <h3 class="profileHeadding">{{profile.display_name}}</h3>
                    <p ng-bind-html="intro"></p>
                </div>
            </div>
        </div>

        <div class="profileSection profileMedia flexbox">
            <div class="profileMediaImage flexFullChild">
                <figure><img src="<?php echo get_template_directory_uri() ?>/custom-theme/images/image-group.jpg" alt="" /></figure>
                <div class="seeMore text-right"><a href="<?php echo site_url() . '/albums' ?>">see more photos</a></div>
            </div>
            <div class="profileMediaVideoWrap">
                <div class="profileMediaVideo"  ng-bind-html="videoDashboard"></div>
                <div class="seeMore text-right"><a href="#">see more videos</a></div>
            </div>
        </div>

        <div class="accordianList">

            <?php get_template_part('template-part/view', 'vitals'); ?>
            
            <?php get_template_part('template-part/view', 'journal'); ?>

            <div class="accordianItem">
                <div class="accordianItemHeader clearfix flexbox verticalAlign">
                    <a href="#" class="buttons text-center"><i class="fa fa-pencil"></i></a>
                    <div class="accordianItemIcons clearfix">
                        <svg version="1.1" role="presentation" viewBox="0 0 1792 1792" class="fa-icon"><path fill="#ffffff" d="M768 1664h896v-640h-416q-40 0-68-28t-28-68v-416h-384v1152zM1024 224v-64q0-13-9.5-22.5t-22.5-9.5h-704q-13 0-22.5 9.5t-9.5 22.5v64q0 13 9.5 22.5t22.5 9.5h704q13 0 22.5-9.5t9.5-22.5zM1280 896h299l-299-299v299zM1792 1024v672q0 40-28 68t-68 28h-960q-40 0-68-28t-28-68v-160h-544q-40 0-68-28t-28-68v-1344q0-40 28-68t68-28h1088q40 0 68 28t28 68v328q21 13 36 28l408 408q28 28 48 76t20 88z"/></svg>
                    </div>
                    <div class="accordianItemHeaderText">Letters</div>
                </div>
                <div class="accordianItemContents">
                    <div class="row articleRow articlePosts clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="articleItemHead clearfix noBg">EXPECTING MOTHER LETTER</div>
                                <div class="articleItemContents noPad">
                                    <p>We hope that our child will be feeling the best at your home,protected and loved.We want to see him grow and develope good characvter traits from your guidance.He seeks good education and well being.We love to receive updates and new feeds.We believe you could become his best friend throughtout the ups and downs of life.</p>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="articleItemHead clearfix noBg">LETTER ABOUT THEM</div>
                                <div class="articleItemContents noPad">
                                    <p>I, {{profile.first_name}} and {{profile.spouse_first_name}} have known each other for XX years. I am writing this letter to attest to the validity of the Marriage between {{profile.first_name}} and {{profile.spouse_first_name}}. I have been a witness to their relationship since they (Dated, were engaged, Got Married, While they were married) and they are truly in love and have no deviant reasons for their marriage other than them wanting to spend their lives together.</p>
                                    <p>Not only have they joined in celebrating (Religious holidays, birthdays, weddings, anniversary’s, family activities, etc.), I attended their (engagement, Marriage, Baby Shower, Etc.) They are a happy and loving couple and I was over joyed to hear the announcement that they are currently expecting their first child on August 7th 2012.</p>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="articleItemHead clearfix noBg"><span class="pull-left">Letter 1</span><span class="pull-right postDate">September 01, 2016</span></div>
                                <div class="articleItemContents noPad">
                                    <figure><img src="http://via.placeholder.com/500x250"></figure>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="articleItemHead clearfix noBg"><span class="pull-left">Letter 2</span><span class="pull-right postDate">September 01, 2016</span></div>
                                <div class="articleItemContents noPad">
                                    <figure><img src="http://via.placeholder.com/500x300"></figure>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                    </div>
                </div>
            </div><!--//Accordian Item-->

            <div class="accordianItem">
                <div class="accordianItemHeader clearfix flexbox verticalAlign">
                    <a href="#" class="buttons text-center"><i class="fa fa-pencil"></i></a>
                    <div class="accordianItemIcons clearfix">
                        <svg version="1.1" role="presentation" viewBox="0 0 1280 1792" class="pf-icon-user fa-icon"><path fill="#ffffff" d="M1280 1399q0 109-62.5 187t-150.5 78h-854q-88 0-150.5-78t-62.5-187q0-85 8.5-160.5t31.5-152 58.5-131 94-89 134.5-34.5q131 128 313 128t313-128q76 0 134.5 34.5t94 89 58.5 131 31.5 152 8.5 160.5zM1024 512q0 159-112.5 271.5t-271.5 112.5-271.5-112.5-112.5-271.5 112.5-271.5 271.5-112.5 271.5 112.5 112.5 271.5z"/></svg>
                    </div>
                    <div class="accordianItemHeaderText">Videos</div>
                </div>
                <div class="accordianItemContents">
                    <div class="row articleRow articlePosts clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="videoItemWrap">
                                    <a href="#" class="videoItemLink"><div class="videoPlayIcon text-center"><span></span></div></a>
                                    <figure><img src="http://via.placeholder.com/500x300"></figure>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="videoItemWrap">
                                    <a href="#" class="videoItemLink"><div class="videoPlayIcon text-center"><span></span></div></a>
                                    <figure><img src="http://via.placeholder.com/500x300"></figure>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="videoItemWrap">
                                    <a href="#" class="videoItemLink"><div class="videoPlayIcon text-center"><span></span></div></a>
                                    <figure><img src="http://via.placeholder.com/500x300"></figure>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost">
                            <div class="articleItem">
                                <div class="videoItemWrap">
                                    <a href="#" class="videoItemLink"><div class="videoPlayIcon text-center"><span></span></div></a>
                                    <figure><img src="http://via.placeholder.com/500x300"></figure>
                                </div>
                            </div>
                        </div><!--//Post Item-->
                    </div>
                </div>
            </div><!--//Accordian Item-->

        </div>

    </div>
    <!--Profile end-->

</section>
<?php get_footer(); ?>
