@extends('layouts.app')

@section('content')
<style>
    .form-label, h2 {
        color: white;
    }

    .form-control {
        background-color: black;
        color: white;
        border: 1px solid white;
    }

    .glow-btn {
        font-size: 20px;
        font-weight: bold;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        text-transform: uppercase;
        display: inline-block;
        transition: 0.3s ease-in-out;
        text-align: center;
    }

    .glow-btn-success {
        background: #28a745;
        color: #fff;
        box-shadow: 0 0 10px #28a745;
        margin-top: 20px;
    }

    .glow-btn-success:hover {
        box-shadow: 0 0 20px #28a745, 0 0 30px #28a745;
    }

    .container {
        text-align: center;
    }
</style>

<div class="container">
    <h2>Upload Materials for {{ $course->name }} ({{ ucfirst($plan) }} Plan)</h2>

    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('courses.storeMaterial', ['id' => $course->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="hidden" name="plan_level" value="{{ $plan }}">

        <div class="mb-3">
            <label class="form-label">Select Subject:</label>
            <select name="subject_id" id="subject_id" class="form-control" required>
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Add Subject Input -->
        <div class="mb-3">
            <label class="form-label">Add New Subject:</label>
            <input type="text" id="new_subject" class="form-control" placeholder="Enter new subject name">
            <button type="button" class="btn btn-primary mt-2" onclick="addSubject()">Add Subject</button>
            <div id="subject-message" class="text-warning mt-2"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Video Title:</label>
            <input type="text" name="video_title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Video:</label>
            <input type="file" name="video[]" class="form-control" multiple accept="video/*" required>
        </div>

        @if($plan === 'standard' || $plan === 'premium')
            <div class="mb-3">
                <label class="form-label">PDF Title:</label>
                <input type="text" name="pdf_title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload PDF:</label>
                <input type="file" name="pdf[]" class="form-control" multiple accept="application/pdf" required>
            </div>
        @endif

        @if($plan === 'premium')
            <div class="mb-3">
                <label class="form-label">Handwritten Notes Title:</label>
                <input type="text" name="handwritten_title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload Handwritten Notes:</label>
                <input type="file" name="handwritten_notes[]" class="form-control" multiple accept="image/*,application/pdf" required>
            </div>
        @endif

        <button type="submit" class="glow-btn glow-btn-success">Upload</button>
    </form>

    <!-- Button to View Uploaded Materials -->
    <div class="text-center mt-4">
        <a href="{{ route('courses.viewMaterials', ['id' => $course->id, 'plan' => $plan]) }}" class="glow-btn glow-btn-success">
            View Uploaded Materials
        </a>
    </div>
</div>

<!-- JavaScript to Add Subject Dynamically and Save to Database -->
<script>
function addSubject() {
    let newSubject = document.getElementById("new_subject").value.trim();
    let subjectMessage = document.getElementById("subject-message");

    if (newSubject === "") {
        subjectMessage.textContent = "Please enter a subject name.";
        return;
    }

    let subjectDropdown = document.getElementById("subject_id");

    // Check if the subject already exists
    for (let i = 0; i < subjectDropdown.options.length; i++) {
        if (subjectDropdown.options[i].text.toLowerCase() === newSubject.toLowerCase()) {
            subjectMessage.textContent = "Subject already exists.";
            return;
        }
    }

    subjectMessage.textContent = "Adding subject...";

    // Save subject to database via AJAX
    fetch("{{ route('courses.addSubject') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            course_id: "{{ $course->id }}",
            name: newSubject
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add new subject as an option
            let newOption = document.createElement("option");
            newOption.text = newSubject;
            newOption.value = data.subject_id;
            newOption.selected = true;
            subjectDropdown.appendChild(newOption);

            // Clear input field and message
            document.getElementById("new_subject").value = "";
            subjectMessage.textContent = "Subject added successfully!";
            setTimeout(() => subjectMessage.textContent = "", 2000);
        } else {
            subjectMessage.textContent = "Error adding subject.";
        }
    })
    .catch(error => {
        subjectMessage.textContent = "An error occurred.";
        console.error("Error:", error);
    });
}
</script>
@endsection
