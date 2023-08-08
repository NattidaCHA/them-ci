<!DOCTYPE html>
<html lang="en">
<?php $finalTitle = ((!empty($title)) ? $title . ' ~ ' : '') . $site_name; ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $finalTitle; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="<?php echo $finalTitle; ?>">
    <meta name="author" content="<?php echo $site_author; ?>">
    <meta name="description" content="">
    <link rel="canonical" href="<?php echo site_url(); ?>">
    <?php if (!empty($CURUSER)) { ?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <?php } ?>
    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16.png">
    <link rel="mask-icon" href="/assets/img/favicon/safari-pinned-tab.svg" color="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <?php
    if (!empty($css)) {
        foreach ($css as $_css) {
            echo $_css;
        }
    }
    ?>

    <link type="text/css" href="/assets/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/style.css?v1.0.2" rel="stylesheet">
    <link type="text/css" href="/assets/css/custom.css?d=<?php echo date('YmdH'); ?>" rel="stylesheet">
    <link type="text/css" href="/assets/css/bootstrap-icons.min.css" rel="stylesheet">
    <script src="/assets/js/jquery.min.js"></script>
    <script>
        var CURRENT_URL = '<?php echo current_url(); ?>';
    </script>
</head>

<body>