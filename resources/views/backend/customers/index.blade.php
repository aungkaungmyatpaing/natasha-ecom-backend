@extends('main')

@section('content')
    <h4 class="mb-3">Customers</h4>
    <div class="card category">
        <div class="card-header d-flex justify-content-between py-4">
                <div class="d-flex align-items-center">
                    <span class="button_content">All Customers</span>
                </div>
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable" style="width:100%;">
                    <thead class="text-center">
                        <th class="text-center ">အမည် / Name</th>
                        <th class="text-center no-sort">အီးမေးလ် / Email</th>
                        <th class="text-center no-search no-sort">ပြင်မည်/ဖျက်မည်</th>
                        <th class="text-center no-search no-sort">Control</th>
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
                ajax: "/customers/datatable/ssd",
                language : {
                  "processing": "<img src='{{asset('/images/loading.gif')}}' width='50px'/><p></p>",
                  "paginate" : {
                    "previous" : '<i class="ri-arrow-left-circle-fill"></i>',
                    "next" : '<i class="ri-arrow-right-circle-fill"></i>',
                  }
                },
                columns : [
                  {data: 'name', name: 'name', class: 'text-center'},
                  {data: 'email', name: 'email' , class: 'text-center'},
                  {data: 'action', name: 'action' , class: 'text-center'},
                  {data: 'is_banned', name: 'is_banned' , class: 'text-center'},
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


            $(document).on('click', '.ban_btn', function(e) {
              e.preventDefault();
              let id = $(this).data('id');
              customerBan('1',id);

            })

            $(document).on('click', '.unban_btn', function(e) {
              e.preventDefault();
              let id = $(this).data('id');
              customerBan('0',id);

            })

            function customerBan(isBanned,id){
                swal({
                text: "Are you sure?",
                icon: "info",
                buttons: true,
                dangerMode: true,
              })
              .then((response) => {
                if (response) {
                  $.ajax({
                    url : `/customers/ban/${id}`,
                    method : 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        'is_banned': isBanned,
                    },
                  }).done(function(res) {
                      table.ajax.reload();
                      Swal.fire({
                            icon: 'success',
                            title: `"${res.customerName}" has been ${ isBanned == '1' ? 'banned' : 'unbanned' } !`,
                            showConfirmButton: false,
                            timer: 1800
                        });
                  })
                }
              });
            }
        })
    </script>
@endsection
