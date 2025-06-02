<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <script src="<?php echo e(asset('js/script.js')); ?>" defer></script>
    <style>
        body {
            overflow-x: hidden;
            background: #0f0f14;
            color: #d4d4d8;
            font-family: 'Nunito', sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* Sidebar */
        .sidebar {
            background: #1c1c24;
            border-right: 1px solid #2a2a33;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 10px;
        }

        .sidebar h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #5e5ce6;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
        }

        .list-group-item {
            background: #25252e;
            border: none;
            margin-bottom: 8px;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        .list-group-item a {
            color:rgb(231, 231, 239) !important; /* Force light gray text for visibility */
            display: flex;
            align-items: center;
            padding: 10px;
        }

        .list-group-item:hover {
            background: #2a2a33;
        }

        .list-group-item.disabled {
            background: transparent;
            color: #5e5ce6;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Action Section Specific Styling */
        .action-section .list-group-item {
            background: #2a2a33; /* Slightly lighter than sidebar bg */
            border-left: 3px solid #5e5ce6; /* Purple accent */
        }

        .action-section .list-group-item:hover {
            background:rgb(209, 209, 217);
        }

        .action-section .list-group-item.disabled {
            color: #7876ff; /* Lighter purple for "Action" header */
            background: transparent;
            border-left: none;
            padding-left: 10px;
        }

        .action-section .list-group-item a {
            color: #d4d4d8 !important; /* Ensure "Action" items are light gray */
        }

        .action-section .list-group-item a:hover {
            color: #5e5ce6 !important; /* Purple on hover for "cool" effect */
        }

        /* Navbar */
        .navbar {
            background: #1c1c24;
            border-bottom: 1px solid #2a2a33;
            padding: 15px 20px;
        }

        .navbar h6 {
            color: #5e5ce6;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-link {
            color: #d4d4d8 !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #5e5ce6 !important;
        }

        .dropdown-menu {
            background: #25252e;
            border: 1px solid #2a2a33;
        }

        .dropdown-item {
            color: #d4d4d8;
        }

        .dropdown-item:hover {
            background: #2a2a33;
            color: #5e5ce6;
        }

        /* Main Content */
        .main-content {
            margin-left: 16.67%;
            padding: 20px;
        }

        /* Dashboard Table */
        .card {
            background: #1c1c24;
            border: 1px solid #2a2a33;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .dashboard-title {
            font-size: 2rem;
            font-weight: 700;
            color: #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 0 0 6px rgba(53, 51, 158, 0.2);
            margin-bottom: 20px;
        }

        .table {
            color: #d4d4d8;
            border-spacing: 0 10px;
        }

        .table thead th {
            font-size: 1.1rem;
            font-weight: 600;
            color: #5e5ce6;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #2a2a33;
            padding: 15px;
        }

        .table tbody tr {
            background: #25252e;
            transition: background 0.3s ease;
        }

        .table tbody tr:hover {
            background: #2a2a33;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .btn-primary {
            background: #5e5ce6;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background:rgb(245, 245, 245);
            box-shadow: 0 0 8px rgba(94, 92, 230, 0.4);
        }

        .btn-secondary {
            background: #3a3a44;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #4a4a55;
            box-shadow: 0 0 8px rgba(58, 58, 68, 0.3);
        }

        /* Social Media */
        .social-icons a {
            color: #d4d4d8;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #5e5ce6;
        }

        /* Footer */
        footer {
            color: #6b7280;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 60px;
                padding: 10px;
            }
            .main-content {
                margin-left: 60px;
            }
            .dashboard-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .table thead th, .table td {
                font-size: 0.85rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <nav class="col-2 sidebar">
                <h1 class="h5 py-2">
                    <i class="fas fa-user-cog me-2"></i>
                    <span class="d-none d-lg-inline">Digital Academy</span>
                </h1>
                <!-- Menu Items -->
                <ul class="list-group mt-4 ms-2">
                    <span class="list-group-item disabled d-none d-lg-block">Controls</span>
                    <li class="list-group-item list-group-item-action">
                        <a href="<?php echo e(url('/admin/dashboard')); ?>">
                            <i class="fas fa-home me-2"></i>
                            <span class="d-none d-lg-inline">Home</span>
                        </a>
                    </li>
                    <li class="list-group-item list-group-item-action">
                        <a href="<?php echo e(url('/admin/category')); ?>">
                            <i class="fas fa-user-edit me-2"></i>
                            <span class="d-none d-lg-inline">Categories</span>
                        </a>
                    </li>
                    <li class="list-group-item list-group-item-action">
                        <a href="<?php echo e(url('/admin/courses')); ?>">
                            <i class="fas fa-chart-line me-2"></i>
                            <span class="d-none d-lg-inline">Courses</span>
                        </a>
                    </li>
                    <li class="list-group-item list-group-item-action">
                     <a href="<?php echo e(url('/admin/reviews')); ?>">
                     <i class="fas fa-book me-2"></i>
                     <span class="d-none d-lg-inline">Reviews</span>
                 </a>
                 </li>
                    <li class="list-group-item list-group-item-action">
                    <a href="<?php echo e(url('/admin/blogs')); ?>">
                    <i class="fas fa-flag me-2"></i>
                      <span class="d-none d-lg-inline">Blogs</span>
                      </a>
                    </li>

                    <li class="list-group-item list-group-item-action">
                        <a href="<?php echo e(url('/')); ?>">
                            <i class="fas fa-globe me-2"></i>
                            <span class="d-none d-lg-inline">View Website</span>
                        </a>
                    </li>
                </ul>
                <!-- Actions -->
                <div class="list-group mt-4 ms-2 mb-2 action-section">
                    <span class="list-group-item disabled d-none d-lg-block">Action</span>
                    <a href="<?php echo e(url('/admin/users')); ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>
                        <span class="d-none d-lg-inline">User List</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-edit me-2"></i>
                        <span class="d-none d-lg-inline">Update Data</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span class="d-none d-lg-inline">Add Events</span>
                    </a>
                </div>
            </nav>

            <main class="col-10 main-content">
                <div class="navbar navbar-expand">
                    <div class="flex-fill">
                        <h6 class="text-center mt-2">Digital Academy Admin</h6>
                    </div>
                    <div class="navbar-nav">
                    <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1"></i> 
                      <?php echo e(Auth::check() ? Auth::user()->name : 'Guest'); ?>

                     </a>
                       <ul class="dropdown-menu dropdown-menu-end">
                   <li><a href="#" class="dropdown-item">Profile</a></li>
                   <li><hr class="dropdown-divider"></li>
             <li>
                   <form action="<?php echo e(route('logout')); ?>" method="POST">
                   <?php echo csrf_field(); ?>
                  <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to logout?')">
                    Logout
                           </button>
                       </form>
                       </li>
                      </ul>
                    </li>

                    </div>
                </div>

                <div class="container-fluid mt-3">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <!-- Social Media -->
                <div class="share">
    <a href="https://www.facebook.com/share/18ZKbBgeNE/" target="_blank" rel="noopener noreferrer" class="fab fa-facebook-f"></a>
    <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" class="fab fa-twitter"></a>
    <a href="https://www.instagram.com/digital_accademy_?utm_source=qr&igsh=MWRtN3QxaTduaXN5bA==" target="_blank" rel="noopener noreferrer" class="fab fa-instagram"></a>
    <a href="https://www.linkedin.com/in/narendra-purohit-58b4bb35a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" target="_blank" rel="noopener noreferrer" class="fab fa-linkedin"></a>
</div>


                <!-- Footer -->
                <footer class="text-center py-4">© Copyright 2025 Digital Academy ™</footer>
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>
    <?php echo $__env->yieldContent('script'); ?>
</body>
</html><?php /**PATH C:\Users\govind\Desktop\project\digital_academy\resources\views/backend/master.blade.php ENDPATH**/ ?>