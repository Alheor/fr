<!--#site#-->
<!DOCTYPE HTML PUBLIC  "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="<?php echo $_link->csscache;?>" rel="STYLESHEET" type="text/css"/>
<title><?php echo $title;?></title>
</head>
<body>
<div class="sitebox">
<div class="header"><?php echo $this->escape($header);?></div>
<div class="body">
    <div class="body_left"><?php echo $leftBody;?></div>
    <div class="body_center"><?php echo $centerBody;?></div>
    <div class="body_right"><?php echo $rightBody;?></div>
</div>
<div class="conter_footer"></div>
</div>
<div class="footer"><?php echo $footer;?></div>
</body>
</html>
<!--#site_end#-->