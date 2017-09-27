<?php
/**
 * 
 * Template Name: Dashboard
 */
get_header();
?>
<section class="container">
    <!--Profile Start-->
    <div class="profile" style="margin-top:25px;">
        
        <?php get_template_part('template-part/view', 'user-basic'); ?>

        <div class="accordianList">

            <?php get_template_part('template-part/view', 'vitals'); ?>
            
            <?php get_template_part('template-part/view', 'journal'); ?>

            <?php get_template_part('template-part/view', 'letter'); ?>

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
