@extends('main')

@section('content')
    <h4 class="mb-3">Orders</h4>
    <div class="card order">
        <div class="card-header d-flex justify-content-between py-4">
            <div class="d-flex align-items-center">
                <span class="button_content">Preorders</span>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable" style="width:100%;">
                    <thead class="text-center">
                        <th class="text-center">နာမည်</th>
                        <th class="text-center no-sort ">နေရပ်လိပ်စာ / Address</th>
                        <th class="text-center no-sort ">ငွေပေးချေမှုနည်းလမ်း / Payment Methods</th>
                        <th class="text-center no-sort no-search">အချိန်</th>
                        <th class="text-center no-sort no-search">Preorder</th>
                        <th class="text-center no-sort no-search">Status</th>
                        <th class="text-center no-sort no-search">Control</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')/orders/datatable/ssd
    <script>
      $(document).ready(function() {
            let table = $('#datatable').DataTable( {
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "/preorders/datatable/ssd",
                language : {
                  "processing": "<img src='{{asset('/images/loading.gif')}}' width='50px'/><p></p>",
                  "paginate" : {
                    "previous" : '<i class="ri-arrow-left-circle-fill"></i>',
                    "next" : '<i class="ri-arrow-right-circle-fill"></i>',
                  }
                },
                columns : [
                  {data: 'name', name: 'name' , class: 'text-center'},
                  {data: 'address', name: 'address' , class: 'text-center'},
                  {data: 'payment_method', name: 'payment_method' , class: 'text-center'},
                  {data: 'created_at', name: 'created_at' , class: 'text-center'},
                  {data: 'preorder_date', name: 'preorder_date' , class: 'text-center'},
                  {data: 'status', name: 'status' , class: 'text-center'},
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

            $(document).on('click', '.cancelBtn', function(e) {
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
                  let status = $(this).attr('data-status');
                  console.log(status);
                  $.ajax({
                    url : `/orders/${id}`,
                    method : 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        'status': status,
                    },
                  }).done(function(res) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Order updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });

                  })
                }
              });
            })
        })
    </script>
@endsection
