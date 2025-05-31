<script>
    $(document).ready(function() {
        function hideerror() {
            setTimeout(() => {
                $('.error').hide();
            }, 5500);
        }

        $(function() {
            $('#date_of_birth').inputmask('yyyy-mm-dd', {
                'placeholder': 'yyyy-mm-dd'
            })
        });

        $("#user-update-frm").on("submit", function(e) {
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
                    $(".custom-control-input").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $(".role-error").remove();
                    loderStart();
                },
                complete: function() {},
                success: function(response) {
                    loderStop();
                    if (response.data.status === "validation_error") {
                        printError(response.data.message);
                    } else if (response.data.status === "error") {
                        toastr.error(response.data.message);
                    } else {
                        toastr.success(response.data.message);
                        window.location = "{{ route('admin.coach.list') }}";
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });

        $("#coach-add-frm").on("submit", function(e) {
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
                    $(".custom-control-input").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $(".role-error").remove();
                    loderStart();
                },
                complete: function() {},
                success: function(response) {
                    loderStop();
                    if (response.data.status === "validation_error") {
                        if (response.data.message.hasOwnProperty('mobile')) {
                            for (let i = 0; i < response.data.message.mobile.length; i++) {
                                let html = '<div class="invalid-feedback">' + response.data
                                    .message.mobile[i] + '</div>';
                                $('#tel').parent().append(html);
                            }
                        }
                        printError(response.data.message);
                        $('.invalid-feedback').each(function() {
                            $(this).addClass('error');
                        });
                        hideerror();
                    } else if (response.data.status === "error") {
                        toastr.error(response.data.message);
                    } else {
                        toastr.success(response.data.message);
                        window.location = "{{ route('admin.coach.list') }}";
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });

        $(document).on('click', '.blockUser', function(e) {
            e.preventDefault();
            console.log('work');
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Archive!',
                                'Your data has been archive.',
                                'success'
                            );
                            row.remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed oparetion.',
                                'error'
                            );
                        }
                    });
                } else {
                    return false;
                }
            })
        });

        $(document).on('click', '.activeUser', function(e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Archive!',
                                'Your data has been archive.',
                                'success'
                            );
                            row.remove();
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed oparetion.',
                                'error'
                            );
                        }
                    });
                } else {
                    return false;
                }
            })
        });

        $(document).on('click', '.blockUserEmail', function(e) {
            e.preventDefault();
            console.log('work');
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, not verified it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Not Verify!',
                                'Your data has been not verify.',
                                'success'
                            );
                            //  row.remove();
                            location.reload();

                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed oparetion.',
                                'error'
                            );
                        }
                    });
                } else {
                    return false;
                }
            })
        });

        $(document).on('click', '.activeUserEmail', function(e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Verify!',
                                'Your data has been verify.',
                                'success'
                            );
                            // row.remove();
                            location.reload();

                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed oparetion.',
                                'error'
                            );
                        }
                    });
                } else {
                    return false;
                }
            })
        });

        $(document).on('click', '.blockUserMobile', function(e) {
            e.preventDefault();
            console.log('work');
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, not verified it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Not Verify!',
                                'Your data has been verify.',
                                'success'
                            );
                            // row.remove();
                            location.reload();

                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed oparetion.',
                                'error'
                            );
                        }
                    });
                } else {
                    return false;
                }
            })
        });

        $(document).on('click', '.activeUserMobile', function(e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Verify!',
                                'Your data has been verify.',
                                'success'
                            );
                            // row.remove();
                            location.reload();

                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed oparetion.',
                                'error'
                            );
                        }
                    });
                } else {
                    return false;
                }
            })
        });





        // $(document).on('click', '.blockUserEmail', function(e) {
        //     e.preventDefault();
        //     console.log('work');
        //     var row = $(this).closest('tr');
        //     var $this = $(this);
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You will be able to revert this!",
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, archive it!'
        //     }).then((result) => {
        //         if (result.value) {
        //             $.post({
        //                 type: $this.data('method'),
        //                 url: $this.attr('href'),
        //             }).done(function (data) {
        //                 if (data.success) {
        //                     Swal.fire(
        //                         'Archive!',
        //                         'Your data has been archive.',
        //                         'success'
        //                     );
        //                   //  row.remove();
        //                   location.reload();

        //                 }else{
        //                     Swal.fire(
        //                         'Error!',
        //                         'Failed oparetion.',
        //                         'error'
        //                     );
        //                 }
        //             });
        //         }else{
        //             return false;
        //         }
        //     })
        // });

        // $(document).on('click', '.activeUserEmail', function(e) {
        //     e.preventDefault();
        //     var row = $(this).closest('tr');
        //     var $this = $(this);
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You will be able to revert this!",
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, archive it!'
        //     }).then((result) => {
        //         if (result.value) {
        //             $.post({
        //                 type: $this.data('method'),
        //                 url: $this.attr('href'),
        //             }).done(function (data) {
        //                 if (data.success) {
        //                     Swal.fire(
        //                         'Archive!',
        //                         'Your data has been archive.',
        //                         'success'
        //                     );
        //                    // row.remove();
        //                    location.reload();

        //                 }else{
        //                     Swal.fire(
        //                         'Error!',
        //                         'Failed oparetion.',
        //                         'error'
        //                     );
        //                 }
        //             });
        //         }else{
        //             return false;
        //         }
        //     })
        // });

        // $(document).on('click', '.blockUserMobile', function(e) {
        //     e.preventDefault();
        //     console.log('work');
        //     var row = $(this).closest('tr');
        //     var $this = $(this);
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You will be able to revert this!",
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, archive it!'
        //     }).then((result) => {
        //         if (result.value) {
        //             $.post({
        //                 type: $this.data('method'),
        //                 url: $this.attr('href'),
        //             }).done(function (data) {
        //                 if (data.success) {
        //                     Swal.fire(
        //                         'Archive!',
        //                         'Your data has been archive.',
        //                         'success'
        //                     );
        //                    // row.remove();
        //                    location.reload();

        //                 }else{
        //                     Swal.fire(
        //                         'Error!',
        //                         'Failed oparetion.',
        //                         'error'
        //                     );
        //                 }
        //             });
        //         }else{
        //             return false;
        //         }
        //     })
        // });

        // $(document).on('click', '.activeUserMobile', function(e) {
        //     e.preventDefault();
        //     var row = $(this).closest('tr');
        //     var $this = $(this);
        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You will be able to revert this!",
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, archive it!'
        //     }).then((result) => {
        //         if (result.value) {
        //             $.post({
        //                 type: $this.data('method'),
        //                 url: $this.attr('href'),
        //             }).done(function (data) {
        //                 if (data.success) {
        //                     Swal.fire(
        //                         'Archive!',
        //                         'Your data has been archive.',
        //                         'success'
        //                     );
        //                    // row.remove();
        //                    location.reload();

        //                 }else{
        //                     Swal.fire(
        //                         'Error!',
        //                         'Failed oparetion.',
        //                         'error'
        //                     );
        //                 }
        //             });
        //         }else{
        //             return false;
        //         }
        //     })
        // });
    });
</script>
