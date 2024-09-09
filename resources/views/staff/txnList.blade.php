@extends('layouts.main')
@section('title', 'Transactions')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row">
    <div class="col-md-12">
        @if(Auth::user()->user_type != 1)
        <div class="row" style="background-image: url('img/backGround.png');background-repeat: no-repeat;background-size: cover;">
            <div class="col-md-4 col-lg-3">
                <div class="card" style="min-height: 200px;padding: 30px 20px;box-shadow: 0px 0px 25px 4px gray;margin-top: 20px;margin-bottom: 20px;">
                    <div class="card-body" style="display: flex;flex-wrap: wrap;align-content: flex-end;justify-content: space-around;">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;font-size: 30px;font-weight: 900;">
                                <img src="img/addMoney.png" style="height: 80px;"><br>Vendor Pay
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary pull-right submit_button w-100" id="vendorPayButton" value="Vendor Pay" style="height: 45px;width: 315px !important;">Vendor Pay</button>
                            </div>

                            <div class="modal" tabindex="-1" role="dialog" id="vendorPayModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Vendor Payment Information</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="vendor" placeholder="Enter Vendor Name" style="height: 45px; margin-top: 20px;">

                                                <input type="number" class="form-control" id="amount" placeholder="Enter Amount" style="height: 45px; margin-top: 20px;">
                                                <input type="text" class="form-control" id="holder" placeholder="Account Holder Name" style="height: 45px; margin-top: 20px;">

                                                <input type="text" class="form-control" id="account" placeholder="Account Number" style="height: 45px; margin-top: 20px;">

                                                <input type="text" class="form-control" id="ifsc" placeholder="IFSC Code" style="height: 45px; margin-top: 20px;">

                                                <input type="FILE" class="form-control" id="invoice" placeholder="Upload Invoice" style="height: 45px;margin-top: 20px;">
                                                <small>Upload Invoice</small>
                                            </div>
                                        </div>


                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="height: 35px;">Close</button>

                                            <button class="btn btn-primary pull-right submit_button w-20" type="submit" id="payNew" name="Save" value="Pay Now" style="height: 35px;">Pay Now </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- rent pay  -->
            <div class="col-md-4 col-lg-3">
                <div class="card" style="min-height: 200px;padding: 30px 20px;box-shadow: 0px 0px 25px 4px gray;margin-top: 20px;margin-bottom: 20px;">
                    <div class="card-body" style="display: flex;flex-wrap: wrap;align-content: flex-end;justify-content: space-around;">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center;font-size: 30px;font-weight: 900;">
                                <img src="img/rent_pay.png" style="height: 80px;"><br>Rent Pay
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary pull-right submit_button w-100" id="rentPay" value="Rent Pay" style="height: 45px; margin-left: 100px;">
                            </div>

                            <div class="modal" tabindex="-1" role="dialog" id="rentPayModal">
                                <div class="modal-dialog" role="document">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Rent Payment Information</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Your input fields go here -->
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="amount_rent" placeholder="Enter Amount" style="height: 45px; margin-top: 20px;">
                                                <input type="text" class="form-control" id="ifsc_rent" placeholder="IFSC Code" style="height: 45px; margin-top: 20px;">
                                                <input type="text" class="form-control" id="holder_rent" placeholder="Account Holder Name" style="height: 45px; margin-top: 20px;">
                                                <input type="text" class="form-control" id="account_rent" placeholder="Account Number" style="height: 45px; margin-top: 20px;">
                                                <input type="text" class="form-control" id="beneficiary_pan_number" placeholder="Beneficiary Pan Number" style="height: 45px; margin-top: 20px;">
                                                <input type="FILE" class="form-control" id="rent_agreement" placeholder="Upload Rent Agreement" style="height: 45px;margin-top: 20px;">
                                                <small>Upload Rent Agreement</small>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="height: 35px;">Close</button>

                                            <input class="btn btn-primary pull-right submit_button w-20" id="payNowNew" type="submit" name="Save" value="Pay Now" style="height: 35px;">
                                        </div>

                                        <!-- <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rent Payment Information</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input type="number" class="form-control" id="amount_rent" placeholder="Enter Amount" style="height: 45px; margin-top: 20px;">
                                                    <input type="text" class="form-control" id="ifsc_rent" placeholder="IFSC Code" style="height: 45px; margin-top: 20px;">
                                                    <input type="text" class="form-control" id="holder_rent" placeholder="Account Holder Name" style="height: 45px; margin-top: 20px;">
                                                    <input type="text" class="form-control" id="account_rent" placeholder="Account Number" style="height: 45px; margin-top: 20px;">
                                                    <input type="text" class="form-control" id="beneficiary_pan_number" placeholder="Beneficiary Pan Number" style="height: 45px; margin-top: 20px;">
                                                    <input type="FILE" class="form-control" id="rent_agreement" placeholder="Upload Rent Agreement" style="height: 45px;margin-top: 20px;">
                                                    <small>Upload Rent Agreement</small>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="height: 35px;">Close</button>
                                               
                                                <input class="btn btn-primary pull-right submit_button w-20" type="submit" id="payNowrent" name="Save" value="Pay Now" style="height: 35px;">
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-3 col-lg-2">
                <div class="card" style="min-height: 120px; ">
                    <div class="card-body" style="display: flex;flex-wrap: wrap;align-content: flex-end;justify-content: space-around;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="dimensions" class="required">{{ __('Amount')}}</label>
                                    <input type="number" class="form-control" id="amount">
                                </div>
                            </div>
                            <div class="col-sm-12 ">
                                <div class="form-group">
                                    <input class="btn btn-primary pull-right submit_button w-100" type="submit" id="payNow" name="Save" value="Pay Now">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-2">
                <div class="card " style="min-height: 120px;">
                    <div class="card-body" style="display: flex;flex-wrap: wrap;align-content: flex-end;justify-content: space-around;">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="form-group">
                                    <h5 for="dimensions" class="text-center">{{ __('Total TXN')}}</h5>
                                </div>
                                <div class="form-group">
                                    <h3 for="dimensions" class="text-center">{{ $count['total']}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-2">
                <div class="card " style="min-height: 120px;">
                    <div class="card-body" style="display: flex;flex-wrap: wrap;align-content: flex-end;justify-content: space-around;">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="form-group">
                                    <h5 for="dimensions" class="text-center">{{ __('Successfull TXN')}}</h5>
                                </div>
                                <div class="form-group">
                                    <h3 for="dimensions" class="text-center">{{ $count['success']}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-2">
                <div class="card " style="min-height: 120px;">
                    <div class="card-body" style="display: flex;flex-wrap: wrap;align-content: flex-end;justify-content: space-around;">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="form-group">
                                    <h5 for="dimensions" class="text-center">{{ __('Failure TXN')}}</h5>
                                </div>
                                <div class="form-group">
                                    <h3 for="dimensions" class="text-center">{{ $count['failure']}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            </div>
            @endif



            <div class="card mt-3">
                <div class="card-header justify-content-between">
                    <h3><i class="ik ik-list"></i> {{ __('Transactions')}}</h3>
                    <div class="pull-right">
                        <div class="row">
                            @if(Auth::user()->user_type != 1)
                            @else
                            <a class="btn btn-outline-primary btn-rounded-20 mr-2" href="{{ route('users') }}">
                                Back
                            </a>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('##')}}</th>
                                <th>{{ __('Name')}}</th>
                                <th>{{ __('Email')}}</th>
                                <th>{{ __('Phone No.')}}</th>
                                <th>{{ __('Client Txn ID')}}</th>
                                <th>{{ __('Txn ID')}}</th>
                                <th>{{ __('Amount')}}</th>
                                <th>{{ __('Status')}}</th>
                                @if(Auth::user()->user_type != 1)
                                <th>{{ __('Refund')}}</th>
                                @endif
                                <th>{{ __('Created Date')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="d-none" id="paymentForm">
    </div>
    @endsection
    <!-- push external js -->
    @push('script')
    <!-- Select the state when select the country -->
    <script>
        $(document).ready(function() {

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#vendorPayButton').click(function() {
                $('#vendorPayModal').modal('show');
            });
        });
        $(document).ready(function() {
            $('#rentPay').click(function() {
                $('#rentPayModal').modal('show');
            });
        });
    </script>
    <!--server side table script start-->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#payNowNew, #payNew').click(function(e) {
                e.preventDefault();
                var postfix = desc = "";
                if ($(this).attr('id') == 'payNowNew') {
                    postfix = '_rent';
                    desc = "{{env('RAZORPAY_DESC_RENT')}}";

                } else {
                    postfix = '';
                    desc = "{{env('RAZORPAY_DESC_VENDOR')}}";
                }
                console.log(postfix, desc)
                if (
                    (
                        $('#amount_rent').val() > 0 && 
                        $('#ifsc_rent').val() !== '' && 
                        $('#holder_rent').val() !== '' && 
                        $('#account_rent').val() !== '' &&
                        $('#beneficiary_pan_number').val() !== '' 
                        && $('#rent_agreement').val() !== ''
                    ) 
                        || 
                    (
                        $('#amount').val() > 0 &&
                        $('#ifsc').val() !== '' &&
                        $('#holder').val() !== '' &&
                        $('#account').val() !== '' &&
                        $('#invoice').val() !== '' &&
                        $('#vendor').val() !== ''
                    )
                    ) {
                        $('.loader').show()
                    $.ajax({
                        url: '/create-order',
                        method: 'GET',
                        data: {
                            amount: $(`#amount${postfix}`).val()
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('.loader').show()
                            var options = {
                                "key": "{{ env('RAZORPAY_KEY_ID') }}", // Enter the Key ID generated from the Dashboard
                                "amount": data.amount, // Amount is in currency subunits. Default currency is INR.
                                "currency": "INR",
                                "name": "{{env('RAZORPAY_COMAPNY_NAME')}}",
                                "description": desc,
                                "image": "{{ asset('company_logo/20230916042110.png') }}",
                                "order_id": data.id, // Pass the `id` obtained in the response from the `createOrder` method
                                "handler": function(response) {
                                    $.ajax({
                                        url: '/payment-callback',
                                        method: 'POST',
                                        contentType: 'application/json',
                                        data: JSON.stringify({
                                            razorpay_payment_id: response.razorpay_payment_id,
                                            razorpay_order_id: response.razorpay_order_id,
                                            razorpay_signature: response.razorpay_signature
                                        }),
                                        success: function(result) {
                                            if(postfix == "")
                                        {
                                            $('#vendorPayModal').modal('hide')
                                        }
                                        else
                                        {
                                            $('#rentPayModal').modal('hide')
                                        }
                                            if (result.status === 'success') {
                                                alert('Payment successful!');
                                                window.location.reload();
                                            } else {
                                                alert('Payment failed!');
                                            }
                                        }
                                    });
                                },
                                "prefill": {
                                    "name": data.name,
                                    "email": data.email,
                                    "contact": data.contact
                                },
                                "theme": {
                                    "color": "#3399cc"
                                }
                            };

                            var rzp1 = new Razorpay(options);
                            rzp1.open();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching order:', error);
                        }
                    });
                }
                else {
                    alert('Please Enter All Fields');
                }
            });
        });







        $('#payNow').click("click", () => {
            if (
                $('#amount').val() > 0 &&
                $('#ifsc').val() !== '' &&
                $('#holder').val() !== '' &&
                $('#account').val() !== '' &&
                $('#invoice').val() !== '' &&
                $('#vendor').val() !== ''
            ) {
                $('.loader').show()
                $('#payNow').attr('disabled', true)
                $.ajax({
                    type: "get",
                    url: "{{ route('paymnetForm') }}",
                    data: {
                        amount: $('#amount').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        $('#paymentForm').html(data.html);
                        $('#submitButton').click();
                        $('.loader').hide()
                        $('#payNow').attr('disabled', false)
                        $('#vendorPayModal').modal('hide');
                        $('#vendorPayModal').load(window.location.href + ' #vendorPayModal');
                    },
                });
            } else {
                alert('Please Enter All Fields');
            }
        });



        $('#payNowrent').click("click", () => {
            if ($('#amount_rent').val() > 0 && $('#ifsc_rent').val() !== '' && $('#holder_rent').val() !== '' && $('#account_rent').val() !== '' &&
                $('#beneficiary_pan_number').val() !== '' && $('#rent_agreement').val() !== '') {
                $('.loader').show()
                $('#payNowrent').prop('disabled', true)
                $.ajax({
                    type: "get",
                    url: "{{ route('paymnetForm') }}",
                    data: {
                        amount: $('#amount_rent').val(),
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        $('#paymentForm').html(data.html);
                        $('#submitButton').click();
                        $('.loader').hide()
                        $('#payNowrent').prop('disabled', false)
                        $('#rentPayModal').modal('hide');
                        $('#rentPayModal').load(window.location.href + ' #rentPayModal');
                    },
                });
            } else {
                alert('Please Enter All Fields');
            }
        });

        $('#paymnetButton').click("click", () => {
            $('#amount').val("");
            $('#modalPayment').show();



            // var settings = {
            // "url": "https://api.boscenter.in/api/BOS/BOSPaymentGateway",
            // "method": "POST",
            // "timeout": 0,
            // "headers": {
            //     "Content-Type": "application/json"
            // },
            // "data": JSON.stringify({
            //     "PayCustomerName": "abc",
            //     "PayCustomerPhoneNo": "9999178520",
            //     "CustomerEmailID": "khan.xxxxxxx@gmail.com",
            //     "PayCartAmount": "100",
            //     "curl": "www.abc.com",
            //     "furl": "www.abc.com",
            //     "surl": "www.abc.com",
            //     "RegistrationID": "BOS-2511"
            // }),
            // };

            // $.ajax(settings).done(function (response) {
            // console.log(response);
            // $('#modalPayment').find('.modal-body').html(response.PreparePOSTForm);
            //     $('#modalPayment').show();
            // });
        })


        //listing data table
        $(document).ready(function() {

            var table = $('#data_table').DataTable({
                responsive: true,
                "bProcessing": true,
                "serverSide": true,
                "lengthMenu": [50, 100, 500],
                ajax: {
                    url: "{{ route('getTxnList') }}",
                    data: function(d) {
                        d.status = $('#status').val();
                        d.user_id = "{{request('ref_')?request('ref_'):''}}";
                    },

                    error: function() {
                        alert("{{__('something_went_wrong')}}");
                    }
                },

                "aoColumns": [{
                        mData: 'id'
                    },
                    {
                        mData: 'name'
                    },

                    {
                        mData: 'email'
                    },

                    {
                        mData: 'phone'
                    },
                    {
                        mData: 'client_txn_id'
                    },
                    {
                        mData: 'txn_id'
                    },
                    {
                        mData: 'amount'
                    },
                    {
                        mData: 'status'
                    },
                    @if(Auth::user()->user_type != 1) {
                        mData: 'refund'
                    },
                    @endif
                    {
                        mData: 'created_at'
                    }

                ],
                "aoColumnDefs": [{
                    "bSortable": false,
                    'aTargets': [-1, -2, -3, -4, -5]
                }, ],
                order: [
                    [0, 'desc']
                ]
            });

            $('.reset').on('click', function(event) {
                event.preventDefault();
                $('#user_type').val('');
                $('#status').val('');
                $('#data_table').DataTable().ajax.reload();
            })


            $('.filter').on('click', function(event) {
                event.preventDefault();
                $('#data_table').DataTable().ajax.reload();
            })




        });




        //deleteItem
        function deleteItem(id) {
            //show confirmation popup
            $.confirm({
                title: 'Delete',
                content: 'Are you sure you want to delete this?',
                buttons: {
                    Cancel: function() {
                        //nothing to do
                    },
                    Sure: {
                        btnClass: 'btn-primary',
                        action: function() {
                            removedata(id = id);
                        },
                    }
                }
            });
        }


        function removedata(id) {
            $.ajax({
                type: "POST",
                url: "{{route('delete.users')}}",
                data: {
                    id: id,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    console.log(data.success)
                    $.notify(data.success, "success");
                    $('#data_table').DataTable().ajax.reload();
                },
            });
        }




        $(document).ready(function() {
            $(document).on('change', '.status-checkbox', function() {
                var id = $(this).data("id");
                if (this.checked) {
                    var value = '1';
                } else {
                    var value = '0';
                }
                updateItemStatus(id = id, type = 'status', value = value);
            })
        });

        //update item
        function updateItemStatus(id, type, value) {
            $.ajax({
                type: "POST",
                url: "{{route('update.status')}}",
                data: {
                    id: id,
                    type: type,
                    value: value,
                    _token: '{{csrf_token()}}'
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.code == 200) {
                        $.notify(response.msg, "success");
                    } else {
                        $.notify(response.msg, "warning");
                    }
                    //reload data table in case of delete item
                    if (type == 'delete') {
                        var active_page = $(".pagination").find("li.active a").text();
                        //reload datatable
                        $('#data_table').dataTable().fnPageChange((parseInt(active_page) - 1));
                    }

                },
            });
        }


        // $(document).on('change', '#user_type,#status', function() {
        //     filter_listing();
        // });

        function filter_listing() {
            var form_data = $(document).find("#filter-subscription").serializeArray()
            get_cities(form_data);
        }



        function refund(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('update.refund_status') }}",
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.message) {
                        alert(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    } else {
                        $.notify('Unknown response', "warning");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $.notify('Error occurred while processing the request', "error");
                }
            });
        }
    </script>
    @endpush