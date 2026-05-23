<?php
// app/templates/footer.php
?>
    </div> <!-- end container-fluid -->
</div> <!-- end content -->
</div> <!-- end wrapper -->

<!-- jQuery and Bootstrap Bundle JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
            if ($('#sidebar').hasClass('active')) {
                $('#sidebar').css('margin-left', '-250px');
                $('#content').css('width', '100%');
            } else {
                $('#sidebar').css('margin-left', '0');
                $('#content').css('width', 'calc(100% - 250px)');
            }
        });
    });
</script>
</body>
</html>
