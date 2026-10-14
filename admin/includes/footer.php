    </main>
  </div>
</div>

<script>
  // Sidebar toggle for small screens
  (function () {
    var burger = document.getElementById('adBurger');
    var sidebar = document.getElementById('adSidebar');
    if (burger && sidebar) {
      burger.addEventListener('click', function () {
        sidebar.classList.toggle('is-open');
      });
    }

    // Ask before any delete button runs
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        if (!window.confirm(form.getAttribute('data-confirm'))) {
          e.preventDefault();
        }
      });
    });

    // Live preview of the picked image
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
      input.addEventListener('change', function () {
        var target = document.querySelector(input.getAttribute('data-preview'));
        if (target && input.files && input.files[0]) {
          target.src = URL.createObjectURL(input.files[0]);
          target.closest('.ad-thumb').style.display = '';
        }
      });
    });
  })();
</script>
</body>
</html>
