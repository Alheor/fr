<!--#menuul#-->
<ul>
<?php echo $li; ?>
</ul>
<!--#menuul_end#-->
<!--#menuli#-->
<li style="margin-bottom: <?php echo @$margin; ?>px;">
<a href="<?php echo $href; ?>" <?php echo @$attr; ?> <?php if($curflag) echo 'class = "asd"';?>><?php echo $title; ?></a>
<?php echo @$child; ?>
</li>
<!--#menuli_end#-->