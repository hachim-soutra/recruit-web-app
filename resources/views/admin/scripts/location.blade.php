<script>
    $(document).ready(function () {
        $("#country").select2({
			placeholder: "Select Country",
		});

        $('#state').select2({
            placeholder: 'Select State',
            tags: true
        });

        $(document).on('change', '#country', function () {
            get_state_list('#country', '#state');
        });

        $('#city').select2({
            placeholder: 'Select City',
            tags: true
        });

        $(document).on('change', '#state', function () {
            get_city_list('#state', '#city');
        });

        function get_state_list(countryTag, stateTag) {
            var country_id = $(countryTag).select2('data')[0].element.attributes[1].value;
            $.ajax({
                type: "GET",
                url: "{{ env("APP_BASE_PATH") }}"+"/admin/get-state/"+country_id,
                dataType: 'json',
                beforeSend: function () {
                    $(stateTag).html('<option></option>');
                    loderStart();
                },
                success: function (response) {
                    var data=response;
                    if (!$.isEmptyObject(data)) {
                        var state_html = '<option></option>';
                        $.each(data, function(i, e) {
                            state_html += '<option value="' + e.name + '" data-state="'+e.id+'">' + e.name + '</option>';
                        });
                        $(stateTag).html(state_html);
                    }
                    loderStop();
                }
            })
        }

        function get_city_list(stateTag, cityTag) {
            var state_id = $(stateTag).select2('data')[0].element.attributes[1].value;
            $.ajax({
                type: "GET",
                url: "{{ env("APP_BASE_PATH") }}"+"/admin/get-city/"+state_id,
                dataType: 'json',
                beforeSend: function () {
                    $(cityTag).html('<option></option>');
                    loderStart();
                },
                success: function (response) {
                    var data=response;
                    if (!$.isEmptyObject(data)) {
                        var city_html = '<option></option>';
                        $.each(data, function(i, e) {
                            city_html += '<option value="' + e.name + '" data-city="'+e.id+'">' + e.name + '</option>';
                        });
                        $(cityTag).html(city_html);
                    }
                    loderStop();
                }
            })
        }
    });
</script>
