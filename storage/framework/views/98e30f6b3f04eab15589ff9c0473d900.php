<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Custom CSS for Logout Button -->
    <style>
        /* General Body Styling */
        body {
            margin: 0;
            padding: 0;
            background-color: #1a1a1a; /* Dark background to match the screenshot */
            position: relative;
            height: 100vh;
        }

        /* Logout Button Styling */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: 2px solid #6f42c1; /* Purple border to match the theme */
            color: #ffffff; /* White text */
            font-size: 1rem;
            font-weight: 400;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #6f42c1; /* Purple background on hover */
            color: #ffffff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Logout Button -->
    <form action="<?php echo e(route('logout')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">
            Logout
        </button>
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\Users\govind\Desktop\project\digital_academy\resources\views/layouts/admin.blade.php ENDPATH**/ ?>