jQuery(document).ready(function($) {
      if (window.history && window.history.pushState) {

        $(window).on('popstate', function() {
          var hashLocation = location.hash;
          var hashSplit = hashLocation.split("#!/");
          var hashName = hashSplit[1];

          if (hashName !== '') {
            var hash = window.location.hash;
            if (hash === '') {
              var evalurl = "{{ url('equal_page_slug_by_app_id', {'app_id': app.session.get('id_approche'), '_locale': app.request.locale}) }}";
              window.location=evalurl;
              return false;
            }
          }
        });

        window.history.pushState('forward', null, './#forward');
      }
});