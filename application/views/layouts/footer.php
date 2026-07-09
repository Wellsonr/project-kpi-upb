    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery (required for some functionality) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Set global variables BEFORE loading app.js -->
<script>
window.base_url = '<?= base_url() ?>';
</script>

<!-- Custom JS (versioned by file mtime to bust browser/Cloudflare cache on change) -->
<script src="<?= base_url('assets/js/app.js') ?>?v=<?= @filemtime(FCPATH.'assets/js/app.js') ?: time() ?>&nocache=<?= time() ?>"></script>

<?php if (isset($additional_js)): ?>
    <?= $additional_js ?>
<?php endif; ?>

<script>
var csrf_token_name = '<?= $this->security->get_csrf_token_name() ?>';
var csrf_hash = '<?= $this->security->get_csrf_hash() ?>';
</script>

</body>
</html>
