@extends('layouts.app')

@section('content')
<style>
    /* Container Styling */
    .container {
        padding: 0 20px 40px; /* Reduced top padding, kept bottom */
        background: #0f0f14;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        position: relative;
        overflow: hidden;
    }

    /* Header Wrapper for Better Title Positioning */
    .header-wrapper {
        width: 100%;
        max-width: 1100px;
        padding: 40px 0 20px; /* Top padding here, reduced bottom */
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 1;
    }

    h2.text-center {
        font-size: 2.25rem;
        font-weight: 800;
        color: #e5e7eb;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 0 6px rgba(94, 92, 230, 0.2);
        margin: 0;
        padding: 10px 20px;
        background: rgba(28, 28, 36, 0.8); /* Subtle card-like background */
        border-radius: 8px;
        border: 1px solid #2a2a33;
    }

    /* Subtle Background Detail */
    .container::before {
        content: '';
        position: absolute;
        top: -20%;
        left: -20%;
        width: 140%;
        height: 140%;
        background: radial-gradient(circle at 50% 50%, rgba(94, 92, 230, 0.08), transparent 60%);
        opacity: 0.7;
        z-index: 0;
    }

    /* Row Styling */
    .row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        width: 100%;
        max-width: 1100px;
        padding: 0 10px;
        z-index: 1;
    }

    .col-md-4 {
        display: flex;
        justify-content: center;
    }

    .mb-3 {
        margin-bottom: 0; /* Grid gap handles spacing */
    }

    /* Card Styling */
    .card {
        background: #1c1c24;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #2a2a33;
        text-align: center;
        transition: all 0.3s ease;
        width: 100%;
        max-width: 320px;
        position: relative;
        overflow: hidden;
    }

    .card:hover {
        border-color: #5e5ce6;
        transform: translateY(-4px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #5e5ce6, transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card:hover::before {
        opacity: 1;
    }

    .card h4 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #d4d4d8;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    /* Button Styling */
    .card a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        font-size: 0.95rem;
        font-weight: 500;
        color: #fff;
        background: #5e5ce6;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .card a:hover {
        background: #7876ff;
        box-shadow: 0 0 10px rgba(94, 92, 230, 0.4);
    }

    .card a::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        transition: all 0.4s ease;
    }

    .card a:hover::after {
        left: 100%;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        h2.text-center {
            font-size: 1.75rem;
        }
        .header-wrapper {
            padding: 30px 0 15px;
        }
        .row {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        .card {
            padding: 15px;
        }
        .card h4 {
            font-size: 1.3rem;
        }
        .card a {
            padding: 8px 16px;
        }
    }

    @media (max-width: 480px) {
        h2.text-center {
            font-size: 1.5rem;
        }
        .header-wrapper {
            padding: 20px 0 10px;
        }
        .row {
            grid-template-columns: 1fr;
        }
        .card {
            max-width: 280px;
        }
        .card h4 {
            font-size: 1.2rem;
        }
        .card a {
            padding: 6px 14px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="container">
    <div class="header-wrapper">
        <h2 class="text-center">Subjects for {{ ucfirst($plan) }} Plan</h2>
    </div>
    
    <div class="row">
        @foreach($subjects as $subject)
        <div class="col-md-4 mb-3">
            <div class="card">
                <h4>{{ $subject->name }}</h4>
                <a href="{{ url('/frontend/materials/' . $subject->id . '/' . $plan) }}">View Materials</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection