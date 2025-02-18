<?php
$src="";
if(isset($_GET['besoin'])){
    $src="besoins/".$_GET['besoin'];
}
if(isset($_GET['devis'])){
    $src="devis/".$_GET['devis'];
}
if(isset($_GET['path'])){
    $src="besoin/".$_GET['path'];
}

function get_file_extension($src) {
    return pathinfo($src, PATHINFO_EXTENSION);
}

?>
<html>
<head>
    <title>PDF Viewer</title>
</head>
<body>
    <?php
        if (get_file_extension($src) == 'png' || get_file_extension($src) == 'jpg' || get_file_extension($src) == 'jpeg' ) { ?>
            <img src="<?php echo $src ?>"  width="90%" height="90%" alt="" srcset="">
        <?php } else{ ?>
            <embed src="<?php echo $src ?> " width="100%" height="100%" />
        <?php }
    ?>
</body>
</html>

