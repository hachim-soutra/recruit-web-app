<script>
    $(document).ready(function() {
        $('#roleCreateFrm').on('submit', function (e) {
            e.preventDefault(e);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $(".form-control").removeClass("is-invalid");
                    $(".custom-control-input").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $(".permissions-error").remove();
                    loderStart();
                },
                complete: function () {},
                success: function (response) {
                    loderStop();
                    console.log(response);
                    if (response.data.status === 'validation_error') {
                        printError(response.data.message);
                    } else if (response.data.status === 'error') {
                        toastr.error(response.data.message);
                        $('#roleCreateFrm').trigger("reset");
                    } else {
                        toastr.success(response.data.message);
                        window.location = "{{ route('admin.role.list') }}";
                    }
                },
                error: function (error) {
                    loderStop();
                    console.log(error);
                },
            })
        });

        $('#roleEditFrm').on('submit', function (e) {
            e.preventDefault(e);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function () {
                   $(".form-control").removeClass("is-invalid");
                    $(".custom-control-input").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $(".permissions-error").remove();
                    loderStart();
                },
                complete: function () {},
                success: function (response) {
                    loderStop();
                    if (response.data.status === 'validation_error') {
                        printError(response.data.message);
                    } else if (response.data.status === 'error') {
                        toastr.error(response.data.message);
                    } else {
                        toastr.success(response.data.message);
                    }
                },
                error: function (error) {
                    loderStop();
                    console.log(error);
                },
            })
        });

        $(document).on('click', '.deleteRow', function(e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function (data) {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                            );
                            row.remove();
                        }else{
                            Swal.fire(
                                'Error!',
                                'Failed oparetion.',
                                'error'
                            );
                        }
                    });
                }else{
                    return false;
                }
            })
        });

    });
</script>
