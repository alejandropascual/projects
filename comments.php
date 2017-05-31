<?php

// =============================================================================
// COMMENTS.PHP
// -----------------------------------------------------------------------------
// Handles output of comments on posts.
// =============================================================================



// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="no-comments"><?php echo __('This post is password protected. Enter the password to view comments.', LANGUAGE_THEME); ?></p>
	<?php
		return;
	}
?>

<!-- edit from here -->


<!-- have comments -->

<?php if ( have_comments() ) : ?>

	<div class="comments-container">
		
		<div class="title">
			<h4><?php comments_number(__('No Comments', LANGUAGE_THEME), __('One Comment', LANGUAGE_THEME), '% '.__('Comments', LANGUAGE_THEME));?></h4>
			<div class="title-sep-container">
				<div class="title-sep"></div>
			</div>
		</div>

		<ol class="commentlist">
			<?php wp_list_comments('callback=aps_comment'); ?>
		</ol>

		<div class="comments-navigation">
		    <div class="alignleft"><?php previous_comments_link(); ?></div>
		    <div class="alignright"><?php next_comments_link(); ?></div>
		</div>
		
	</div>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
	
		<!-- If comments are open, but no comments. -->

	 <?php else : // comments are closed ?>
	 
		<!-- If comments closed. -->
		<p class="no-comments"><?php echo __('Comments are closed.', LANGUAGE_THEME); ?></p>

	<?php endif; ?>
	
<?php endif; ?>



<?php if ( comments_open() ) : ?>

    <?php

    function aps_comment_form_default_fields($fields)
    {
        $commenter = wp_get_current_commenter();
        $req       = get_option( 'require_name_email' );
        
        $fields['author'] = '<div id="comment-input"><input type="text" name="author" id="author" value="'. esc_attr( $commenter['comment_author'] ) .'" placeholder="'. __("Name (required)", LANGUAGE_THEME).'" size="22" tabindex="1"'. ( $req ? 'aria-required="true"' : '' ).' class="input-name" />';

        $fields['email'] = '<input type="text" name="email" id="email" value="'. esc_attr( $commenter['comment_author_email'] ) .'" placeholder="'. __("Email (required)", LANGUAGE_THEME).'" size="22" tabindex="2"'. ( $req ? 'aria-required="true"' : '' ).' class="input-email"  />';

        $fields['url'] = '<input type="text" name="url" id="url" value="'. esc_attr( $commenter['comment_author_url'] ) .'" placeholder="'. __("Website", LANGUAGE_THEME).'" size="22" tabindex="3" class="input-website" /></div>';
        
        return $fields;
    }
    add_filter('comment_form_default_fields','aps_comment_form_default_fields');

    $args = array(

        'title_reply'           => '<div><h4>'.__("Leave a Comment", LANGUAGE_THEME).'</h4></div>',
        'title_reply_to'        => '<div><h4>'.__("Leave a Comment", LANGUAGE_THEME).'</h4></div>',
        'must_log_in'           => '<p class="must-log-in">' .  sprintf( __( "You must be %slogged in%s to post a comment.", LANGUAGE_THEME ), '<a href="'.wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ).'">', '</a>' ) . '</p>',
        'logged_in_as'          => '<p class="logged-in-as">' . __( "Logged in as",LANGUAGE_THEME ).' <a href="' .admin_url( "profile.php" ).'">'.$user_identity.'</a>. <a href="' .wp_logout_url(get_permalink()).'" title="' . __("Log out of this account", LANGUAGE_THEME).'">'. __("Log out &raquo;", LANGUAGE_THEME).'</a></p>',
        'comment_notes_before'  => '',
        'comment_notes_after'   => '',
        'comment_field'         => '<div id="comment-textarea"><textarea name="comment" id="comment" cols="39" rows="4" tabindex="4" class="textarea-comment" placeholder="'. __("Comment...", LANGUAGE_THEME).'"></textarea></div>',
        'id_submit'             => 'comment-submit',
        'label_submit'          => __("Post Comment", LANGUAGE_THEME),

    );

    comment_form( $args );

    ?>

<?php endif;