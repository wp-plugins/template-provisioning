<!-- CSS PROVISIONING (searching for 'css/global.css', 'css/ie/global.css', 'css/<?php echo $template_basename; ?>.css', 'css/ie/<?php echo $template_basename; ?>.css') -->
<?php if ($css_href_global) : ?><link rel="stylesheet" href="<?php echo $css_href_global; ?>" type="text/css" media="screen" /><?php endif; ?>
<?php if ($css_href_global_ie) : ?><!--[if IE]><link rel="stylesheet" href="<?php echo $css_href_global_ie; ?>" type="text/css" media="screen" /><![endif]--><?php endif; ?>
<?php if ($css_href_template) : ?><link rel="stylesheet" href="<?php echo $css_href_template; ?>" type="text/css" media="screen" /><?php endif; ?>
<?php if ($css_href_template_ie) : ?><!--[if IE]><link rel="stylesheet" href="<?php echo $css_href_template_ie; ?>" type="text/css" media="screen" /><![endif]--><?php endif; ?>

