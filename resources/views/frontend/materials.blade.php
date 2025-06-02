@extends('layouts.app')

@section('title', 'Digital Academy')

@section('content')
<style>
    /* Container Styling */
    .container {
        padding: 50px 20px;
        background: #0f0f14;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h2 {
        font-size: 2.25rem;
        font-weight: 700;
        text-align: center;
        color: #e0e0e0;
        margin-bottom: 40px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    /* Material Row */
    .material-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        width: 100%;
        max-width: 1200px;
    }

    /* Material Card */
    .material-card {
        background: #1c1c24;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #2a2a33;
        transition: border-color 0.3s ease;
    }

    .material-card:hover {
        border-color: #5e5ce6;
    }

    .material-card h4 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #d4d4d8;
        margin-bottom: 15px;
    }

    /* Video Container */
    .material-card .video-container {
        position: relative;
        max-width: 100%;
        margin-bottom: 15px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #2a2a33;
    }

    .video-container video {
        width: 100%;
        height: auto;
        display: block;
        background: #000;
    }

    .video-container .video-info {
        position: absolute;
        bottom: 8px;
        left: 8px;
        color: #e0e0e0;
        font-size: 0.95rem;
        font-weight: 500;
        background: rgba(0, 0, 0, 0.7);
        padding: 4px 10px;
        border-radius: 4px;
    }

    /* Buttons and Links */
    .material-card a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: background 0.3s ease;
        margin-top: 10px;
    }

    .material-card a.view-btn {
        background: #5e5ce6;
    }

    .material-card a.view-btn:hover {
        background: #7876ff;
    }

    .material-card a.download-btn {
        background: #2a2a33;
        border: 1px solid #5e5ce6;
    }

    .material-card a.download-btn:hover {
        background: #3a3a44;
    }

    /* Preview Image */
    .preview-img {
        max-width: 110px;
        max-height: 110px;
        border-radius: 8px;
        border: 1px solid #5e5ce6;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    .preview-img:hover {
        border-color: #7876ff;
    }

    /* Lightbox */
    #lightbox {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        text-align: center;
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    #lightbox span {
        color: #e0e0e0;
        font-size: 40px;
        position: absolute;
        top: 15px;
        right: 25px;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    #lightbox span:hover {
        color: #5e5ce6;
    }

    #lightbox-img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
        border: 2px solid #5e5ce6;
    }

    /* No Materials Message */
    p {
        text-align: center;
        font-size: 1.2rem;
        color: #a1a1aa;
        margin-top: 25px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        h2 {
            font-size: 1.75rem;
        }
        .material-row {
            grid-template-columns: 1fr;
        }
        .material-card {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.5rem;
        }
        .material-card h4 {
            font-size: 1.25rem;
        }
        .material-card a {
            padding: 8px 16px;
        }
        .preview-img {
            max-width: 90px;
            max-height: 90px;
        }
    }
</style>

<div class="container">
    <h2>Materials for {{ $course->name }} ({{ ucfirst($plan) }} Plan)</h2>

    @if($materials->isEmpty())
        <p>No materials uploaded yet.</p>
    @else
        <div class="material-row">
            @foreach($materials as $material)
                <div class="material-card">
                    <h4>{{ ucfirst($material->type) }}: {{ $material->title ?? 'Untitled' }}</h4>

                    @if($material->type === 'video')
                        <div class="video-container">
                            <video controls preload="metadata">
                                <source src="{{ asset('storage/' . $material->file_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="video-info">{{ $material->title ?? 'Untitled' }}</div>
                        </div>
                    @elseif($material->type === 'pdf')
                        <a href="{{ asset('storage/' . $material->file_path) }}" class="view-btn" target="_blank">
                            <i class="fas fa-eye"></i> View PDF
                        </a>
                    @elseif($material->type === 'handwritten')
                        <img src="{{ asset('storage/' . $material->file_path) }}" class="preview-img" onclick="openLightbox('{{ asset('storage/' . $material->file_path) }}')">
                    @endif

                    <a href="{{ asset('storage/' . $material->file_path) }}" class="download-btn" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Lightbox Modal for Handwritten Notes -->
<div id="lightbox">
    <span onclick="closeLightbox()">×</span>
    <img id="lightbox-img">
</div>

<script>
function openLightbox(src) {
    const lightbox = document.getElementById("lightbox");
    const img = document.getElementById("lightbox-img");
    img.src = src;
    lightbox.style.display = "flex";
    document.body.style.overflow = "hidden";
}

function closeLightbox() {
    const lightbox = document.getElementById("lightbox");
    lightbox.style.display = "none";
    document.body.style.overflow = "auto";
}

document.getElementById("lightbox").addEventListener("click", function(e) {
    if (e.target.tagName !== "IMG") closeLightbox();
});

document.addEventListener("keydown", function(e) {
    if (e.key === "Escape" && document.getElementById("lightbox").style.display === "flex") {
        closeLightbox();
    }
});
</script>

@endsection