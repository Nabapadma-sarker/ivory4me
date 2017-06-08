<?php

    if( !isset($icon_type) || $icon_type == '' )  $icon_type = 'default';
    if( $size != 'small' )
        $size = '';
    
    if( $size != '' ) $size = '-' . $size;
    
    if( is_null($title) || $title == '' ) $title = ucfirst($type);
	
	$target = (isset($target) && $target != '') ? ' target="' . $target . '"' : '';
    
?>

<?php if ( $icon_type == 'default' ) : ?>
    <a href="<?php echo $href; ?>" class="social<?php echo $size . ' ' . $icon_type . ' ' . $type; ?>" title="<?php echo $title; ?>"<?php echo $target; ?>></a>

<?php elseif ( $icon_type == 'black-border' ) : ?>
    <a href="<?php echo $href; ?>" class="social<?php echo $size . ' ' . $icon_type . ' ' . $type; ?>" title="<?php echo $title; ?>"<?php echo $target; ?>></a>

<?php elseif ( $icon_type == 'rounded' ) : ?>
    <div class="<?php echo $icon_type ?> fade-socials<?php echo $size . ' ' . $type . $size; ?>">
    <a href="<?php echo $href; ?>" class="fade-socials<?php echo $size . ' ' . $type . $size; ?>" title="<?php echo $title; ?>" <?php echo $target; ?> style="display: none;" ></a>
    </div>
<?php endif; ?>