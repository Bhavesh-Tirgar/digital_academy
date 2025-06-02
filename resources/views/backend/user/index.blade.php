@extends('backend.master')
@section('title', 'Digital AcademysS')
@extends('layouts.admin')
@section('content')
<style>
    /* Container Styling */
    .container-fluid.user-list-container {
        padding: 30px;
        background: #0f0f14 !important;
        min-height: 100vh;
    }

    /* Title Styling */
    .user-list-title {
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: linear-gradient(90deg, #5e5ce6, #7876ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 30px;
        text-align: center;
        text-shadow: 0 0 8px rgba(94, 92, 230, 0.2);
    }

    /* Alert Styling */
    .alert-success {
        background: #1c1c24;
        color: #34d399;
        border: none;
        border-left: 4px solid #34d399;
        border-radius: 6px;
        padding: 8px 15px;
        font-size: 0.85rem;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .alert-dismissible .btn-close {
        filter: invert(1);
        padding: 0.5rem 1rem;
    }

    /* Table Container */
    .table-container {
        background: #1c1c24 !important;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Table Styling */
    .table.user-list-table {
        color:rgb(0, 0, 4) !important;
        border-spacing: 0 6px;
        border-collapse: separate;
        margin-bottom: 0;
    }

    .table.user-list-table thead th {
        font-size: 0.9rem;
        font-weight: 600;
        color: #5e5ce6 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: transparent !important;
        border: none !important;
        padding: 10px 15px;
        text-align: center;
    }

    .table.user-list-table tbody tr {
        background: #25252e !important;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .table.user-list-table tbody tr:hover {
        background: #2a2a33 !important;
        box-shadow: 0 2px 8px rgba(94, 92, 230, 0.1);
    }

    .table.user-list-table td {
        padding: 10px 15px;
        vertical-align: middle;
        border: none !important;
        font-size: 0.85rem;
        text-align: center;
        color:rgb(0, 0, 10) !important;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-action {
        padding: 5px 10px;
        font-size: 0.8rem;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit {
        background: #5e5ce6;
        color: #fff;
    }

    .btn-edit:hover {
        background: #7876ff;
        box-shadow: 0 0 8px rgba(94, 92, 230, 0.4);
        transform: scale(1.05);
    }

    .btn-delete {
        background: #ef4444;
        color: #fff;
    }

    .btn-delete:hover {
        background: #f87171;
        box-shadow: 0 0 8px rgba(239, 68, 68, 0.4);
        transform: scale(1.05);
    }

    /* Empty State */
    .empty-state {
        color: #6b7280;
        font-size: 0.9rem;
        padding: 20px;
        text-align: center;
    }

    /* Pagination */
    .pagination {
        justify-content: center;
        margin-top: 25px;
    }

    .page-item .page-link {
        background: #25252e;
        color:rgb(1, 1, 11);
        border: none;
        border-radius: 6px;
        margin: 0 4px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .page-item.active .page-link {
        background: #5e5ce6;
        color: #fff;
        border: none;
    }

    .page-item .page-link:hover {
        background: #3a3a44;
        color: #5e5ce6;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .user-list-title {
            font-size: 1.25rem;
        }
        .table.user-list-table thead th, .table.user-list-table td {
            font-size: 0.8rem;
            padding: 8px 10px;
        }
        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }
        .btn-action {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
    }

    @media (max-width: 576px) {
        .user-list-title {
            font-size: 1.1rem;
        }
        .table-container {
            padding: 10px;
        }
        .table.user-list-table thead th, .table.user-list-table td {
            font-size: 0.75rem;
            padding: 6px 8px;
        }
        .btn-action {
            font-size: 0.7rem;
            padding: 3px 6px;
        }
    }
</style>

<div class="row justify-content-center">
    <div class="col-12 text-center">
        <h1 class="user-list-title">User List</h1>
    </div>
    <div class="col-lg-10 col-xl-8">
        @if(session('info'))
            <div class="alert alert-success alert-dismissible show fade">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="table-container">
            <table class="table table-borderless user-list-table" style="background: #1c1c24 !important;">
                <thead>
                    <tr>
                        <th>Index</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="background: #25252e !important;">
                        <td>{{ $loop->index + $users->firstItem() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ date('d-M-y', strtotime($user->created_at)) }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ url('admin/users/'.$user->id.'/edit') }}" class="btn btn-action btn-edit">
                                    <i class="far fa-edit"></i> Edit
                                </a>
                                <form action="{{ url('admin/users/'.$user->id.'/delete') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Are you sure you want to delete?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr style="background: #25252e !important;">
                        <td colspan="6" class="empty-state">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection