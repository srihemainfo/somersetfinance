<script>


      $(document).ready(function() { 
          
        $(document).on('click', '.removeClient', function() {
                var caseId = $(this).data('case-id');
                var clientId = $(this).data('client-id');

                Swal.fire({
                    title: "Are You Sure?",
                    text: "Do You  Want remove Assigned Client ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    reverseButtons: true
                }).then(function(frameby) {
                    if (frameby.value) {
                        $loading.show()
                        $.ajax({
                            method: 'POST',
                            url: "{{ route('admin.application.removeClientGet') }}",
                             headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                            data: {
                                caseId: caseId,
                                clientId: clientId,
                            },
                            success: function(response) {

                                if (response.status && response.message) {

                                    Swal.fire('', response.message, 'success');

                                } else {
                                    Swal.fire('', response.message, 'error');
                                }

                                $loading.hide()

                                callAjax();

                            },
                            error: function(data) {
                                console.log('Error:', data);
                                $loading.hide()
                            }
                        })

                    }
                })

            });
        $(document).on('click', '.assignClient', function() {
                $('#clientAssignForm').trigger("reset");
                var caseId = $(this).data('case-id');
                var caseNo = $(this).data('ref-id');
                $('#job_id').val(caseId);
                $('#case_No').val(caseNo);
                $('#client_id').html('')

                $loading.show()

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.application.clientGets') }}",
                     headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    data: {
                        caseId: caseId
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.data) {


                            let driver_options = '<option value="">-- select Client --</option>'

                            response.data.forEach(function(item) {
                                driver_options +=
                                    `<option value="${item.id}">${item.name}</option>`
                            })

                            $('#client_id').html(driver_options)

                            $('#form-modal').modal('show');
                        } else {

                            Swal.fire('', 'No Client', 'error');

                        }
                     
                        $loading.hide()
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $loading.hide()
                    }
                })


            });
        $('#saveBtn').click(function(e) {
                e.preventDefault();

                var client = $('#client_id').val();
                if (!client) {
                    Swal.fire('', ' Choose the Client', 'error');
                    return
                }
                $loading.show()
                $.ajax({
                    data: $('#clientAssignForm').serialize(),
                    url: "{{ route('admin.application.assignClient') }}",
                     headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {

                        if (response.status) {

                            if (response.message) {
                                $('#form-modal').modal('hide');
                                callAjax()
                                Swal.fire("", response.message, "success");

                            } else {

                                Swal.fire('', 'No Client', 'error');
                                callAjax()
                            }


                        } else {
                            Swal.fire('', response.message, 'error');
                        }

                        $loading.hide()


                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $loading.hide()
                    }
                });
            });
          
      });

      function uploaddocument(id) {

            if (id == undefined) {
                Swal.fire('', 'ID Not Found', 'warning');
            } else {

                $('.secondLoader').show()
                $.ajax({
                    url: "{{ route('admin.application-stage.uploadcheck') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {

                        $('.secondLoader').hide()
                        let status = response.status;
                        if (status == true) {

                            $url = response.url;

                            if ($url != '') {
                                window.location.href = $url;

                            } else {

                                Swal.fire('', 'No any upload option', 'error');
                            }

                        } else {
                            Swal.fire('', response.data, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status) {
                            if (jqXHR.status == 500) {
                                Swal.fire('', 'Request Timeout / Internal Server Error', 'error');
                            } else {
                                Swal.fire('', jqXHR.status, 'error');
                            }
                        } else if (textStatus) {
                            Swal.fire('', textStatus, 'error');
                        } else {
                            Swal.fire('', 'Request Failed With Status: ' + jqXHR.statusText,
                                "error");
                        }
                    }
                })

            }

        }
</script>