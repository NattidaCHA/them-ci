<?php if (isset($__header)) { echo $__header."\n"; } ?>

<?php if (isset($__sidebar)) { echo $__sidebar."\n"; } ?>

<main class="content">
    <?php if (isset($__topbar)) { echo $__topbar."\n"; } ?>

    <div id="main-body">
    <?php echo $__body."\n";  ?>
    </div>

    <?php if (isset($__footer)) { echo $__footer."\n"; } ?>
</main>

</body>
</html>