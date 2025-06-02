@extends('layouts.app')

@section('content')
<style>
    /* Container Styling */
    .container {
        padding: 40px 15px;
        background: #121217;
        min-height: 100vh;
        color: #d1d5db;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    h2.text-center {
        font-size: 2rem;
        font-weight: 700;
        color: #e5e7eb;
        margin-bottom: 40px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #e5e7eb;
        margin-bottom: 20px;
    }

    h4 {
        font-size: 1.2rem;
        font-weight: 500;
        color: #9ca3af;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Plan Buttons Row */
    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        width: 100%;
        max-width: 900px;
    }

    .col-md-4 {
        flex: 1;
        min-width: 200px;
        max-width: 280px;
    }

    .plan-btn {
        width: 100%;
        padding: 12px;
        font-size: 1.1rem;
        font-weight: 500;
        text-transform: uppercase;
        border: none;
        border-radius: 8px;
        color: #fff;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .plan-btn[data-plan="basic"] {
        background: #4b5563;
    }

    .plan-btn[data-plan="basic"]:hover {
        background: #6b7280;
    }

    .plan-btn[data-plan="standard"] {
        background: #d97706;
    }

    .plan-btn[data-plan="standard"]:hover {
        background: #f59e0b;
    }

    .plan-btn[data-plan="premium"] {
        background: #991b1b;
    }

    .plan-btn[data-plan="premium"]:hover {
        background: #b91c1c;
    }

    /* Subjects and Materials Sections */
    #subjects-section, #materials-section {
        width: 100%;
        max-width: 900px;
        margin-top: 30px;
    }

    .list-group {
        list-style: none;
        padding: 0;
    }

    .list-group-item {
        background: #1f1f27;
        border: 1px solid #2d2d37;
        border-radius: 6px;
        padding: 12px 15px;
        margin-bottom: 10px;
        color: #d1d5db;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .subject-item {
        cursor: pointer;
    }

    .subject-item:hover {
        background: #2a2a33;
        border-color: #5e5ce6;
        color: #fff;
    }

    .list-group-item a {
        color: #5e5ce6;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .list-group-item a:hover {
        color: #7876ff;
    }

    /* Containers for Materials */
    #videos-container, #pdfs-container, #notes-container {
        margin-top: 20px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        h2.text-center {
            font-size: 1.75rem;
        }
        h3 {
            font-size: 1.3rem;
        }
        h4 {
            font-size: 1rem;
        }
        .col-md-4 {
            min-width: 150px;
        }
        .plan-btn {
            font-size: 1rem;
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        h2.text-center {
            font-size: 1.5rem;
        }
        .list-group-item {
            font-size: 0.9rem;
            padding: 10px 12px;
        }
    }
</style>

<div class="container">
    <h2 class="text-center">Choose Your Plan</h2>
    
    <!-- Plan Selection -->
    <div class="row">
        <div class="col-md-4">
            <button class="plan-btn" data-plan="basic">Basic</button>
        </div>
        <div class="col-md-4">
            <button class="plan-btn" data-plan="standard">Standard</button>
        </div>
        <div class="col-md-4">
            <button class="plan-btn" data-plan="premium">Premium</button>
        </div>
    </div>
    
    <!-- Subjects List -->
    <div id="subjects-section" class="mt-4" style="display: none;">
        <h3>Available Subjects</h3>
        <ul id="subjects-list" class="list-group"></ul>
    </div>
    
    <!-- Course Materials -->
    <div id="materials-section" class="mt-4" style="display: none;">
        <h3>Course Materials</h3>
        <div id="videos-container" style="display: none;">
            <h4>Videos</h4>
            <ul id="videos-list" class="list-group"></ul>
        </div>
        
        <div id="pdfs-container" style="display: none;">
            <h4>PDFs</h4>
            <ul id="pdfs-list" class="list-group"></ul>
        </div>
        
        <div id="notes-container" style="display: none;">
            <h4>Handwritten Notes</h4>
            <ul id="notes-list" class="list-group"></ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.plan-btn').forEach(button => {
            button.addEventListener('click', function () {
                let planType = this.getAttribute('data-plan');
                
                // Fetch subjects from database
                fetch(`/get-subjects/${planType}`)
                    .then(response => response.json())
                    .then(subjects => {
                        document.getElementById("subjects-section").style.display = "block";
                        document.getElementById("subjects-list").innerHTML = "";
                        
                        subjects.forEach(subject => {
                            let li = document.createElement("li");
                            li.textContent = subject.name;
                            li.classList.add("list-group-item", "subject-item");
                            li.setAttribute("data-subject-id", subject.id);
                            document.getElementById("subjects-list").appendChild(li);
                        });
                    })
                    .catch(error => console.error('Error fetching subjects:', error));
            });
        });
        
        document.getElementById("subjects-list").addEventListener("click", function (event) {
            if (event.target.classList.contains("subject-item")) {
                let subjectId = event.target.getAttribute("data-subject-id");
                
                // Fetch course materials from database
                fetch(`/get-materials/${subjectId}`)
                    .then(responsepoz => response.json())
                    .then(materials => {
                        document.getElementById("materials-section").style.display = "block";

                        let videoContainer = document.getElementById("videos-container");
                        let pdfContainer = document.getElementById("pdfs-container");
                        let notesContainer = document.getElementById("notes-container");

                        document.getElementById("videos-list").innerHTML = "";
                        document.getElementById("pdfs-list").innerHTML = "";
                        document.getElementById("notes-list").innerHTML = "";

                        videoContainer.style.display = "none";
                        pdfContainer.style.display = "none";
                        notesContainer.style.display = "none";

                        materials.forEach(material => {
                            let li = document.createElement("li");
                            li.classList.add("list-group-item");

                            if (material.type === "video") {
                                videoContainer.style.display = "block";
                                li.innerHTML = `<a href="${material.file_path}" target="_blank">${material.title}</a>`;
                                document.getElementById("videos-list").appendChild(li);
                            } else if (material.type === "pdf") {
                                pdfContainer.style.display = "block";
                                li.innerHTML = `<a href="${material.file_path}" target="_blank">${material.title}</a>`;
                                document.getElementById("pdfs-list").appendChild(li);
                            } else if (material.type === "notes") {
                                notesContainer.style.display = "block";
                                li.innerHTML = `<a href="${material.file_path}" target="_blank">${material.title}</a>`;
                                document.getElementById("notes-list").appendChild(li);
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching materials:', error));
            }
        });
    });
</script>
@endsection