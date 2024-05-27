@extends('main')

@section('content')
    <h4 class="mb-3">Payments</h4>
    <div class="card category">
        <div class="card-header d-flex justify-content-between py-4">
            <a class="primary_button" href="{{route('payment.create')}}">
                <div class="d-flex align-items-center">
                    <i class=" ri-add-circle-fill mr-2 primary-icon"></i>
                    <span class="button_content">Payment အသစ်ဖန်တီးမည်</span>
                </div>
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable" style="width:100%;">
                    <thead class="text-center">
                        <th class="text-center no-sort no-search">Image</th>
                        <th class="text-center">အမျိုးအစား / Payment Type</th>
                        <th class="text-center no-sort">နာမည်</th>
                        <th class="text-center no-sort">Payment နံပါတ်</th>
                        <th class="text-center no-sort">ငွေကြေးအမျိုးအစား</th>
                        <th class="text-center no-sort no-search">ပြင်မည်/ဖျက်မည်</th>
                    </thead>
                </table>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
      $(document).ready(function() {
            let table = $('#datatable').DataTable( {
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "/payments/datatable/ssd",
                language : {
                  "processing": "<img src='{{asset('/images/loading.gif')}}' width='50px'/><p></p>",
                  "paginate" : {
                    "previous" : '<i class="ri-arrow-left-circle-fill"></i>',
                    "next" : '<i class="ri-arrow-right-circle-fill"></i>',
                  }
                },
                columns : [
                  {data: 'image', name: 'image', class: 'text-center'},
                  {data: 'payment_type', name: 'payment_type' , class: 'text-center'},
                  {data: 'name', name: 'name' , class: 'text-center'},
                  {data: 'number', name: 'number' , class: 'text-center'},
                  {data: 'available_currency', name: 'available_currency' ,class: 'text-center'},
                  {data: 'action', name: 'action', class: 'text-center'},
                ],
                columnDefs : [
                  {
                    targets : 'hidden',
                    visible : false,
                    searchable : false,
                  },
                  {
                    targets : 'no-sort',
                    sortable : false,
                  },
                  {
                    targets : 'no-search',
                    searchable : false,
                  },
                  {
                    targets: [0],
                    class : "control"
                  },
                ]
            });

            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 1800,
              width : '18em',
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            $(document).on('click', '.delete_btn', function(e) {
              e.preventDefault();
              swal({
                text: "Are you sure?",
                icon: "info",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  let id = $(this).data('id');
                  $.ajax({
                    url : `/payments/${id}`,
                    method : 'DELETE',
                  }).done(function(res) {
                      table.ajax.reload();
                      Toast.fire({
                      icon: 'success',
                      title: "အောင်မြင်ပါသည်။"
                    })
                  })
                }
              });
            })
        })
    </script>
@endsection
