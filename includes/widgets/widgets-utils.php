<?php
function aps_dame_widget_templates()
{
    $templates = array();

    $templates['text'] = <<<TMPL
        <div class="wd-post-item-wrap">
		    <div class="wd-item-content">
		        <div class="title"><a href="%link%">%title%</a></div>
				<div class="date">%date%</div>
		    </div>
        </div>
TMPL;

    $templates['image'] = <<<TMPL
<div class="wd-post-item-wrap">
    <div class="wd-item-image">
        <a href="%link%"><img src="%image_square_url%"></a>
    </div>
</div>
TMPL;

    $templates['image_and_text_square'] = <<<TMPL
        <div class="wd-post-item-wrap">
            <div class="wd-item-image">
				<a href="%link%"><img src="%image_square_url%"></a>
		    </div>
		    <div class="wd-item-content">
		        <div class="title"><a href="%link%">%title%</a></div>
				<div class="date">%date%</div>
		    </div>
        </div>
TMPL;

    $templates['image_and_text_land'] = <<<TMPL
        <div class="wd-post-item-wrap">
            <div class="wd-item-image">
				<a href="%link%"><img src="%image_land_url%"></a>
		    </div>
		    <div class="wd-item-content">
		        <div class="title"><a href="%link%">%title%</a></div>
				<div class="date">%date%</div>
		    </div>
        </div>
TMPL;

    $templates['image_and_text_nocrop'] = <<<TMPL
        <div class="wd-post-item-wrap">
            <div class="wd-item-image">
				<a href="%link%"><img src="%image_nocrop_url%"></a>
		    </div>
		    <div class="wd-item-content">
		        <div class="title"><a href="%link%">%title%</a></div>
				<div class="date">%date%</div>
		    </div>
        </div>
TMPL;


    return $templates;
}