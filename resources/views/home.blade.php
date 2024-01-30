@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}
                    <div class="mb-2" style="float: right;">
                        <a class="btn btn-success" onClick="addAccount()" data-bs-toggle="modal" data-bs-target="#exampleModal"> Create User</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive" id="datatable-crud">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('modal_create')
        <script type="text/javascript">
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#datatable-crud').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('home') }}",
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        },
                    ],
                    order: [
                        [0, 'desc']
                    ]
                });

                $('body').on('click', '.delete', function() {
                    if (confirm("Delete Record?") == true) {
                        var id = $(this).data('id');
                        // ajax
                        $.ajax({
                            type: "PATCH",
                            url: "home/deleteUser/" + id,
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(res) {
                                var oTable = $('#datatable-crud').dataTable();
                                oTable.fnDraw(false);
                            }
                        });
                    }
                });
            });
        </script>
        @endsection