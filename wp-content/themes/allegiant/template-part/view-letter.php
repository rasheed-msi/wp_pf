<?php
global $user_ID;
?>
<style>
    #selected-assoc, #associate-img-container {
        margin: 25px 0 0 0;
        padding: 20px 0 0 0;
        width: 100%;
        font-size: 14px;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    #selected-assoc ul li {list-style:none;}
    .top-buffer { margin-top:20px; }
</style>
<div id="letter-section" class="accordianItem">
    <div class="accordianItemHeader clearfix flexbox verticalAlign">
        <a href="#" class="buttons text-center"><i class="fa fa-pencil"></i></a>
        <div class="accordianItemIcons clearfix">
            <svg version="1.1" role="presentation" viewBox="0 0 1792 1792" class="fa-icon"><path fill="#ffffff" d="M768 1664h896v-640h-416q-40 0-68-28t-28-68v-416h-384v1152zM1024 224v-64q0-13-9.5-22.5t-22.5-9.5h-704q-13 0-22.5 9.5t-9.5 22.5v64q0 13 9.5 22.5t22.5 9.5h704q13 0 22.5-9.5t9.5-22.5zM1280 896h299l-299-299v299zM1792 1024v672q0 40-28 68t-68 28h-960q-40 0-68-28t-28-68v-160h-544q-40 0-68-28t-28-68v-1344q0-40 28-68t68-28h1088q40 0 68 28t28 68v328q21 13 36 28l408 408q28 28 48 76t20 88z"/></svg>
        </div>
        <div class="accordianItemHeaderText"><?php _e('Letters'); ?></div>
    </div>
    <div class="accordianItemContents">
        <div class="row articleRow articlePosts clearfix">
            <div class="loader"></div>
        </div>
    </div>
</div><!--//Accordian Item-->