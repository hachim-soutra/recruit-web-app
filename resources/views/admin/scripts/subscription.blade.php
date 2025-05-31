<script>
    $(document).ready(function () {

        $(document).on('click', '.blockActivateSubscription', function(e) {
            e.preventDefault();
            var row = $(this).closest('tr');
            var $this = $(this);
            Swal.fire({
                title: 'Are you sure for activate this subscription?',
                text: "You will be able to revert this!",
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
                    }).done(function (data) {
                        if (data.success) {
                            toastr.success('Selected subscription has been activated.');
                            setTimeout(() => {
                                window.location = window.location;
                            }, 1000);

                        }else{
                            toastr.error(data.error.message);
                        }
                    });
                }else{
                    return false;
                }
            })
        });


        $(document).on('click', '.activeIns', function(e) {
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
                confirmButtonText: 'Yes, active it!'
            }).then((result) => {
                if (result.value) {
                    $.post({
                        type: $this.data('method'),
                        url: $this.attr('href'),
                    }).done(function (data) {
                        if (data.success) {
                            Swal.fire(
                                'Active!',
                                'Your data has been active.',
                                'success'
                            );
                            window.location = window.location;
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

        $(document).on('click', '.copy-link-button', function(e) {
            e.preventDefault();
            navigator.clipboard.writeText($(this).data('id')).then(() => {
                toastr.success("Payment link copied to clipboard");
            },() => {
                toastr.error('Failed to copy job link');
            });
        });
    });
</script>
