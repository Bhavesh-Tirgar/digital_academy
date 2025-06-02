@extends('backend.master')
@extends('layouts.admin')

@section('title', 'Digital Academy')

@section('content')
<style>
    /* Container Styling */
    .container {
        padding: 30px 20px;
        background: #0f0f14;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .row {
        width: 100%;
        max-width: 900px;
    }

    .col-md-12 {
        width: 100%;
    }

    /* Card Styling */
    .card {
        background: #1c1c24;
        border-radius: 12px;
        padding: 25px;
        border: 1px solid #2a2a33;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Table Styling */
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        color: #d4d4d8;
    }

    .table tr {
        background: #25252e;
        transition: background 0.3s ease;
    }

    .table tr:hover {
        background: #2a2a33;
    }

    .table th {
        font-size: 1.1rem;
        font-weight: 600;
        color: #5e5ce6;
        padding: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid #2a2a33;
    }

    .table td {
        font-size: 1rem;
        padding: 15px;
        vertical-align: middle;
        border: none;
    }

    /* Button Styling */
    .btn {
        padding: 6px 12px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #fff;
        border: none;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #5e5ce6;
    }

    .btn-primary:hover {
        background: #7876ff;
        box-shadow: 0 0 8px rgba(94, 92, 230, 0.4);
    }

    .btn-light {
        background: #3a3a44;
    }

    .btn-light:hover {
        background: #4a4a55;
        box-shadow: 0 0 8px rgba(58, 58, 68, 0.3);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 20px 15px;
        }
        .card {
            padding: 20px;
        }
        .table th, .table td {
            font-size: 0.9rem;
            padding: 12px;
        }
        .btn {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .table {
            border-spacing: 0 8px;
        }
        .table th, .table td {
            font-size: 0.85rem;
            padding: 10px;
        }
        .btn {
            padding: 4px 8px;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <table class="table text-center">
                    <tr>
                        <th>Index</th>
                        <th>Content</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>All User List</td>
                        <td>
                            <button class="btn btn-primary">{{ count($users) }}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Categories</td>
                        <td>
                            <button class="btn btn-light">{{ count($categories) }}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Courses</td>
                        <td>
                            <button class="btn btn-primary">{{ count($courses) }}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Subjects</td>
                        <td>
                            <button class="btn btn-light">{{ count($subjects) }}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Course Materials</td>
                        <td>
                            <button class="btn btn-primary">{{ count($materials ?? []) }}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Reviews</td>
                        <td>
                            <button class="btn btn-light">{{ count($reviews ?? []) }}</button>
                        </td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Blogs</td>
                        <td>
                            <button class="btn btn-primary">{{ count($blogs) }}</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection