<script>
    $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        // CKEDITOR.replace('editor1')
        //bootstrap WYSIHTML5 - text editor
        //Datemask dd/mm/yyyy
        $('#job_expiry_date').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd'})
    })
    $(document).ready(function () {

        $("#employer").select2({
			placeholder: "Select Employer",
		});

        $("#job_skills").select2({
			placeholder: "Select Job Skills",
		});

        $("#functional_area").select2({
			placeholder: "Select Functional Area",
		});

        $("#preferred_job_type").select2({
			placeholder: "Preferred Job Type",
		});
        $("#qualifications").select2({
			placeholder: "Select Preferred Qualifications",
		});
        $("#salary_period").select2({
			placeholder: "Select Salary Period",
		});
        $("#hide_salary").select2({
			placeholder: "Select Salary Hide Status",
		});

        $("#jobCreateFrm").on("submit", function(e) {
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
                        window.location = "{{ route('admin.job.list') }}";
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });

        $("#jobEditFrm").on("submit", function(e) {
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
                        window.location = "{{ route('admin.job.list') }}";
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });

        $(document).on('click', '#showInHomeFrm', function(e) {
            e.preventDefault();
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, active it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                        beforeSend: function() {
                            loderStart();
                        },
                    }).done(function (data) {
                        loderStop();
                        if (data.code === 200) {
                            Swal.fire(
                                'Done!',
                                'Your job has been published to home.',
                                'success'
                            );
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }else{
                            Swal.fire(
                                'Error!',
                                'Operation Failed.',
                                'error'
                            );
                        }
                    });
                }else{
                    return false;
                }
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


        $('#functional_area').select2({
            placeholder: 'Select Functional Area',
            minimumInputLength: 0,

            ajax: {
                url: '{{ route("admin.get_functional_area") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $("#instituteCreateFrm").on("submit", function(e) {
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
                          $('#exampleModal').modal('hide');
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });

        $(document).on('change', '.job-status', function (e) {
            e.preventDefault();
            var $this = $(this);
            var id = $(this).attr('id');
            var status = $(this).val();
            $.confirm({
                title: 'Product Status!',
                content: 'Are you want to change Job status to ' + status + '?',
                buttons: {
                    confirm: function () {
                        loderStart();
                        $.post({
                            type: "PUT",
                            url: "{{ route('admin.job.change_status') }}",
                            data: {
                                'id': id,
                                'status': status
                            }
                        }).done(function (data) {
                            if (data.success) {
                                toastr.success(data.success.message);
                            } else {
                                toastr.error(data.error.message);
                            }
                            loderStop();
                        });
                    },
                    cancel: function () {}
                }
            });
        });

	});
</script>
