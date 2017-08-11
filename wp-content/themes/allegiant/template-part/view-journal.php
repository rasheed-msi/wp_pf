<?php
global $user_ID;
?>

<div class="accordianItem">
    <div class="accordianItemHeader clearfix flexbox verticalAlign">
        <a href="#" class="buttons text-center"><i class="fa fa-pencil"></i></a>
        <div class="accordianItemIcons clearfix">
            <svg version="1.1" role="presentation" viewBox="0 0 1792 1792" class="fa-icon"><path fill="#ffffff" d="M888 1184l116-116-152-152-116 116v56h96v96h56zM1328 464q-16-16-33 1l-350 350q-17 17-1 33t33-1l350-350q17-17 1-33zM1408 1058v190q0 119-84.5 203.5t-203.5 84.5h-832q-119 0-203.5-84.5t-84.5-203.5v-832q0-119 84.5-203.5t203.5-84.5h832q63 0 117 25 15 7 18 23 3 17-9 29l-49 49q-14 14-32 8-23-6-45-6h-832q-66 0-113 47t-47 113v832q0 66 47 113t113 47h832q66 0 113-47t47-113v-126q0-13 9-22l64-64q15-15 35-7t20 29zM1312 320l288 288-672 672h-288v-288zM1756 452l-92 92-288-288 92-92q28-28 68-28t68 28l152 152q28 28 28 68t-28 68z"/></svg>
        </div>
        <div class="accordianItemHeaderText">Journals</div>
    </div>
    <div class="accordianItemContents">
        <div class="row articleRow articlePosts clearfix">
            <?php
            $journal_args = array('post_type' => 'journal', 'author' => $user_ID, 'post_status' => 'publish', 'orderby' => 'date');
            $journal_query = new WP_Query($journal_args);
            if ($journal_query->have_posts()) : while ($journal_query->have_posts()) : $journal_query->the_post();
                    ?>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 articleColumn articlePost edit-journal-container">
                        <a href="#" class="buttons text-center edit-journal" id="post-<?php the_ID();?>"><i class="fa fa-pencil"></i></a>
                        <div class="articleItem">
                            <div class="articleItemHead clearfix noBg"><span class="pull-left " id="post-title-<?php the_ID();?>"><?php the_title(); ?></span><span class="pull-right postDate"><?php the_date('F d, Y'); ?></span></div>
                            <div class="articleItemContents noPad" id="post-content-<?php the_ID();?>">
                                <!--<figure><img src="http://via.placeholder.com/500x400"></figure>-->
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div><!--//Post Item-->
                    <?php
                endwhile;
            else:
                ?><?php
            endif;
            ?>
        </div><!--//Post Item-->
    </div>
</div><!--//Accordian Item-->