        </main>
    </div>

    <!-- SweetAlert ve Bildirim Scripti -->
    <script>
        function showNotification(type, message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1e293b',
                color: '#fff'
            });
        }
    </script>
</body>
</html>
