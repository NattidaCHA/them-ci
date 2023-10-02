<!-- <div class="card theme-settings bg-gray-800 theme-settings-expand" id="theme-settings-expand">
    <div class="card-body bg-gray-800 text-white rounded-top p-3 py-2">
        <span class="fw-bold d-inline-flex align-items-center h6">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
            </svg>
            Settings
        </span>
    </div>
</div> -->

<footer class="footer_tem p-2 mt-4">
    <div class="footer-section">
        <div class="footer-container">
            <div class="contact">
                <div class="mb-1"><a class="text-white" href="<?php echo WWW . '/contactus'; ?>" target="_blank">ติดต่อเรา</a>
                </div>
                <img class="mb-1" src="https://npismodev.scg.com/images/footer/scg-contact-center.png" style="width: 250px;">
                <div>+66 2555 0888</div>
            </div>
            <div class=" contact">
                <div>เว็บไซต์ที่เกี่ยวข้อง</div>
                <a href="http://npi-pipe.com/" target="_blank"><img src="https://npismodev.scg.com/images/footer/scg-corporate.png"></a>
            </div>
            <div class="contact">
                <a title="กรมพัฒนาธุรกิจการค้า Trustmarkthai" style="padding-left: 5px;" href="https://www.trustmarkthai.com/callbackData/popup.php?data=868adfb-21-5-aee1146171eb8a769661219b1a008d08f8e177&markID=firstmar" target="_blank"><img style="max-width: 100%;max-height: 75px; background-color:#fff" alt="กรมพัฒนาธุรกิจการค้า Trustmarkthai" src="https://www.trustmarkthai.com/trust_banners/bns_registered.png"></a>
            </div>
        </div>
        <div class="text-com">
            Ⓒ สงวนลิขสิทธิ 2019 บริษัท นวพลาสติกอุตสาหกรรม (สระบุรี) จำกัด
        </div>
    </div>
</footer>

<?php if (ENVIRONMENT == 'development') { ?>
    <script src="/assets/vendor/@popperjs/umd/popper.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/moment.min.js"></script>
    <script src="/assets/vendor/smooth-scroll/smooth-scroll.polyfills.min.js"></script>
    <script src="/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

<?php } else { ?>
    <script src="/invoicenotification/assets/vendor/@popperjs/umd/popper.min.js"></script>
    <script src="/invoicenotification/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/invoicenotification/assets/js/moment.min.js"></script>
    <script src="/invoicenotification/assets/vendor/smooth-scroll/smooth-scroll.polyfills.min.js"></script>
    <script src="/invoicenotification/assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<?php } ?>

<?php
if (!empty($js)) {
    foreach ($js as $_js) {
        echo $_js;
    }
}
?>

<script src="<?php echo $http ?>/assets/js/function.js?v=1.03"></script>

<script>
    Swal = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mx-4',
            denyButton: 'btn btn-danger mx-4',
            cancelButton: 'btn btn-gray-500 mx-4'
        },
        buttonsStyling: false
    });
</script>