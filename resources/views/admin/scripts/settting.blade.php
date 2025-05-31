<script>
    $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    // CKEDITOR.replace('editor1')
    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5()
    //Datemask dd/mm/yyyy
    $('#event_date').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
     //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
    $("#new_join_one_month_free").select2({
			placeholder: "One Month Free For",
		});
    
  })
    $(document).ready(function () {

        $("#settingEditFrm").on("submit", function(e) {
            e.preventDefault(e);
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".form-control").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    loderStart();
                },
                complete: function() {},
                success: function(response) {
                    loderStop();
                    console.log(response);
                    if (response.data.status === "validation_error") {
                        printError(response.data.message);
                    } else if (response.data.status === "error") {
                        toastr.error(response.data.message);
                    } else {
                        toastr.success(response.data.message);
                        window.location = "{{ route('admin.setting.show') }}";
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });

	});
</script>
<script>
    function initialize() {
        var address = (document.getElementById('addres_one'));
        var autocomplete = new google.maps.places.Autocomplete(address);
        autocomplete.setTypes(['geocode']);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }
            document.getElementById("start_latitude").value = place.geometry.location.lat();
            document.getElementById("start_longitude").value = place.geometry.location.lng();
            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
