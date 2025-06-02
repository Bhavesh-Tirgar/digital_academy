

<?php $__env->startSection('content'); ?>
<style>
    /* Space between cards */
    .custom-card {
        width: 400px;
        padding: 30px;
        text-align: center;
        border-radius: 15px;
        background-color: #111;
        box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
        margin-bottom: 40px; /* Increased space between cards */
    }

    /* Glowing Heading */
    .glow-heading {
        font-size: 15px;
        font-weight: bold;
        text-transform: uppercase;
        color: #fff;
        text-shadow: 0 0 10px #fff, 0 0 20px #28a745, 0 0 30px #28a745;
    }

    /* Glowing Button */
    .glow-btn {
        font-size: 20px;
        font-weight: bold;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        text-transform: uppercase;
        display: inline-block;
        transition: 0.3s ease-in-out;
    }

    .glow-btn-warning {
        background: #ffc107;
        color: #000;
        box-shadow: 0 0 10px #ffc107;
    }

    .glow-btn-warning:hover {
        box-shadow: 0 0 20px #ffc107, 0 0 30px #ffc107;
    }

    .glow-btn-primary {
        background: #007bff;
        color: #fff;
        box-shadow: 0 0 10px #007bff;
    }

    .glow-btn-primary:hover {
        box-shadow: 0 0 20px #007bff, 0 0 30px #007bff;
    }

    .glow-btn-danger {
        background: #dc3545;
        color: #fff;
        box-shadow: 0 0 10px #dc3545;
    }

    .glow-btn-danger:hover {
        box-shadow: 0 0 20px #dc3545, 0 0 30px #dc3545;
    }
</style>

<div class="container text-center">
    <h2 class="glow-heading mb-4">Manage Course Material</h2>

    <div class="d-flex flex-column align-items-center">
        <!-- Basic Plan Card -->
        <div class="custom-card">
            <a href="<?php echo e(route('courses.uploadMaterial', ['id' => 1, 'plan' => 'basic'])); ?>" 
               class="glow-btn glow-btn-warning">BASIC</a>
        </div>

        <!-- Standard Plan Card -->
        <div class="custom-card">
            <a href="<?php echo e(route('courses.uploadMaterial', ['id' => 2, 'plan' => 'standard'])); ?>" 
               class="glow-btn glow-btn-primary">STANDARD</a>
        </div>

        <!-- Premium Plan Card -->
        <div class="custom-card">
            <a href="<?php echo e(route('courses.uploadMaterial', ['id' => 3, 'plan' => 'premium'])); ?>" 
               class="glow-btn glow-btn-danger">PREMIUM</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\govind\Desktop\project\digital_academy\resources\views/backend/courses/index.blade.php ENDPATH**/ ?>