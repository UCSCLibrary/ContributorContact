
<div class="field">
    <div id="emailContribsPermalink-label" class="two columns alpha">
        <label for="emailContribsPermalink"><?php echo __('Send confirmation message?'); ?></label>
    </div>
    <div class="inputs five columns omega">
<?php  
     $props= array();
     if(get_option('emailContribsPermalink')==true)
         $props=array('checked'=>'checked');
     echo get_view()->formCheckbox('emailContribsPermalink', 'true',$props); ?>
        <p class="explanation"><?php echo __(
            'Would you like Omeka to email contributors automatically '
            .'when their contributions are made public?'
        ); 
?></p>
    </div>
</div>


<div class="field">
    <div id="contribsPermalinkSubject-label" class="two columns alpha">
        <label for="contribsPermalinkSubject"><?php echo __('Subject'); ?></label>
    </div>
    <div class="inputs five columns omega">
<?php echo get_view()->formText('contribsPermalinkSubject',get_option('contribsPermalinkSubject'),array()); ?>
        <p class="explanation"><?php echo __('Enter the subject of the email you would like to send automatically to contributors when their contribution becomes live'); ?></p>
    </div>
</div>


<div class="field">
    <div id="contribsPermalinkMessage-label" class="two columns alpha">
        <label for="contribsPermalinkMessage"><?php echo __('Message'); ?></label>
    </div>
    <div class="inputs five columns omega">
<?php echo get_view()->formTextarea('contribsPermalinkMessage',get_option('contribsPermalinkMessage'),array()); ?>
        <p class="explanation"><?php echo __('Enter the text of the email you would like to send automatically to contributors when their contribution becomes live. If you enter [link] omeka will replace that with a link to the contributed item.'); ?></p>
    </div>
</div>

