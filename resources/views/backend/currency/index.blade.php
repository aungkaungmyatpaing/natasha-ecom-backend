@extends('main')

@section('content')
    <h4 class="mb-3">Currency Rate</h4>
    <div class="card brand">
        <div class="card-header d-flex justify-content-between py-4">
            <a class="primary_button" href="{{route('currency.create')}}">
                <div class="d-flex align-items-center">
                    <i class=" ri-add-circle-fill mr-2 primary-icon"></i>
                    <span class="button_content">Currency Rate အသစ်ဖန်တီးမည်</span>
                </div>
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable" style="width:100%;">
                    <thead class="text-center">
                        <th class="text-center no-sort no-search">From</th>
                        <th class="text-center no-sort ">Rate</th>
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
                ajax: "/currencies/datatable/ssd",
                language : {
                  "processing": "<img src='{{asset('/images/loading.gif')}}' width='50px'/><p></p>",
                  "paginate" : {
                    "previous" : '<i class="ri-arrow-left-circle-fill"></i>',
                    "next" : '<i class="ri-arrow-right-circle-fill"></i>',
                  }
                },
                columns : [
                  {data: 'from_currency_unit', name: 'from_currency_unit', class: 'text-center'},
                  {data: 'currency_rate', name: 'currency_rate', class: 'text-center'},
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
                    url : `/currencies/${id}`,
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
