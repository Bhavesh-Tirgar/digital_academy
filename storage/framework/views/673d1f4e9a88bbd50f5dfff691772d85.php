<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DIGITAL ACADEMY</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">

    <!-- custom js file link  -->
    <script src="<?php echo e(asset('js/script.js')); ?>" defer></script>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/script.js')); ?>" defer></script>

    <!-- Updated Inline CSS for Review Section Only -->
    <style>
        /* Review Section Container */
        .reviews {
            background-color: #ecf0f1;
            padding: 60px 20px;
            text-align: center;
        }

        .reviews h2 {
            font-size: 36px;
            color: #1a2a44;
            margin-bottom: 40px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Success Message */
        .alert-success {
            max-width: 600px;
            margin: 0 auto 30px;
            padding: 15px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 10px;
            font-size: 16px;
        }

        /* Review Card Styling */
        .reviews .review-card {
            background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
            padding: 25px;
            margin: 0 auto 25px;
            max-width: 750px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e5e5;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
            overflow: hidden;
        }

        .reviews .review-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .reviews .review-card p {
            font-size: 16px;
            color: #6c757d;
            margin: 10px 0;
            line-height: 1.7;
        }

        .reviews .review-card strong {
            font-size: 22px;
            color: #1a2a44;
            font-weight: 700;
        }

        .reviews .review-card small {
            font-size: 14px;
            color: #95a5a6;
            display: block;
            margin-top: 12px;
        }

        .reviews .btn-danger {
            background: #e74c3c;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .reviews .btn-danger:hover {
            background: #d62f1f;
            transform: translateY(-2px);
        }

        /* Review Form Styling */
        .reviews form {
            max-width: 750px;
            margin: 40px auto 0;
            padding: 30px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f4f8 100%);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            border: 1px solid #e5e5e5;
        }

        .reviews .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .reviews .form-group label {
            font-size: 18px;
            color: #1a2a44;
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
        }

        /* Styling for Select Course dropdown */
        .reviews .form-group select[name="course_id"] {
            width: 100%;
            padding: 14px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            background: #ffffff;
            color: #e74c3c; /* Default color (red) for "Choose A Course" */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            appearance: none; /* Remove default arrow in some browsers */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%231a2a44' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .reviews .form-group select[name="course_id"]:focus {
            border-color: #2980b9;
            box-shadow: 0 0 8px rgba(41, 128, 185, 0.3);
            outline: none;
        }

        /* General styling for other selects and textarea */
        .reviews .form-group select:not([name="course_id"]),
        .reviews .form-group textarea {
            width: 100%;
            padding: 14px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            background: #ffffff;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            appearance: none; /* Remove default arrow in some browsers */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%231a2a44' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .reviews .form-group select:not([name="course_id"]):focus,
        .reviews .form-group textarea:focus {
            border-color: #2980b9;
            box-shadow: 0 0 8px rgba(41, 128, 185, 0.3);
            outline: none;
        }

        .reviews .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .reviews .btn-primary {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #2980b9 0%, #1f6391 100%);
            border: none;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .reviews .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(41, 128, 185, 0.4);
        }

        /* Class for when a course is selected */
        .reviews .form-group select[name="course_id"].selected {
            color: #2980b9; /* Blue color for selected course, matching the dropdown options */
        }
    </style>
</head>

<body style="background-color:#6495ed;">
    
<!-- header section starts  -->

<div id="menu-btn" class="fas fa-bars"></div>

<header class="header">
    <a href="#" class="logo"> <i class="fas fa-graduation-cap"></i> DIGITAL ACADEMY</a>

    <nav class="navbar1">
        <a href="#home"> <i class="fas fa-angle-right"></i> home </a>
        <a href="#courses"> <i class="fas fa-angle-right"></i> courses </a>
        <a href="#reviews"> <i class="fas fa-angle-right"></i> reviews </a>
        <a href="#blogs"> <i class="fas fa-angle-right"></i> blogs </a> 
        <a href="#contact"> <i class="fas fa-angle-right"></i> contact </a>

        <?php if(Auth::check()): ?>
        <a href="<?php echo e(url('/register')); ?>"> <i class="fas fa-angle-right"></i> <?php echo e(Auth::user()->name); ?> (<?php echo e(Auth::user()->status); ?>)</a>
        <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="fas fa-angle-right"></i> logout
        </a>

        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?>
        </form>
        <?php else: ?>
        <a href="<?php echo e(url('/login')); ?>"> <i class="fas fa-angle-right"></i> login </a>
        <a href="<?php echo e(url('/register')); ?>"> <i class="fas fa-angle-right"></i> register </a>
        <?php endif; ?>
    </nav>

    <div class="share">
    <a href="https://www.facebook.com/share/18ZKbBgeNE/" target="_blank" rel="noopener noreferrer" class="fab fa-facebook-f"></a>
    <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" class="fab fa-twitter"></a>
    <a href="https://www.instagram.com/digital_accademy_?utm_source=qr&igsh=MWRtN3QxaTduaXN5bA==" target="_blank" rel="noopener noreferrer" class="fab fa-instagram"></a>
    <a href="https://www.linkedin.com/in/narendra-purohit-58b4bb35a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" target="_blank" rel="noopener noreferrer" class="fab fa-linkedin"></a>
</div>

    <p class="credit">Education is <span>the key</span> to heaven.</p>
</header>

<!-- header section ends -->

<!-- home section starts  -->

<section class="home" id="home" style="background-color:#6495ed;">
    <div class="image">
        <img src="images/home-img.svg" alt="">
        <div class="transition">
            <span style="font-size:28px ;">🌼</span>
        </div>
    </div>

    <div class="content">
        <span>online education</span>
        <h3>Knowledge will give you power. <a href="#">get started</a></h3>
        <p>Push yourself, because no one else is going to do it for you.</p>
        <a href="#courses" class="btn1">our courses</a> 
    </div>
</section>

<!-- home section ends -->

<!-- img slider start -->

<section style="background-color:#8b0000 ;">
    <div class="slider">
        <div class="slider-track">
            <div class="slider">
                <img src="images/course-1.svg" width="250" height="100" alt="">
            </div>
            <div class="slider">
                <img src="images/course-2.svg" width="250" height="100" alt="">
            </div>
            <!-- Add other slides here as needed -->
        </div>
    </div>
</section>

<!-- img slider end  -->

<!-- category section starts  -->

<section class="category" id="category" style="background-color:#6495ed;">
    <div class="heading">
        <span>our category</span>
        <h3>our top category</h3>
    </div>

    <div class="box-container">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="box">
            <i class="fas fa-code"></i>
            <h3 class="text-white"><?php echo e($category->name); ?></h3>
            <p></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<!-- category section ends -->

<!-- about section starts  -->

<section class="about" id="about" style="background-color:#6495ed;">
    <div class="image">
        <img src="images/about-img.svg" alt="">
    </div>

    <div class="content">
        <span>about us</span>
        <h3>best online platform for e-learning.</h3>
        <p>Easy learning with records. Repeat learning life-time.</p>
        <a href="#" class="btn1">read more</a>
    </div>
</section>

<!-- about section ends -->

<!-- courses section starts  -->

<section class="courses" id="courses">
    <div class="heading">
        <span>our top courses</span>
        <h3>popular courses</h3>
    </div>

    <div class="box-container">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="box">
            <div class="image">
                <img src="images/course-1.svg" alt="">
                <h3><?php echo e($category->name); ?></h3>
            </div>
            <div class="content">
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3 class="text-primary"><?php echo e($category->name); ?></h3>
                <p class="text-primary"><?php echo e($category->content); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<!-- courses section ends -->

<!-- pricing section starts  -->

<section class="pricing" id="pricing">
    <div class="heading">
        <span>choose a plan</span>
        <h3>affordable plans</h3>
    </div>

    <div class="box-container">
        <div class="box">
            <h3>basic plan</h3>
            <img src="images/price-1.svg" alt="">
            <div class="amount"> <span>$</span>30<span>/month</span> </div>
            <div class="list">
                <p> <i class="fas fa-check"></i> full courses </p>
                <p> <i class="fas fa-check"></i> online exams </p>
                <p> <i class="fas fa-check"></i> certificate </p>
                <p> <i class="fas fa-times"></i> full modules </p>
                <p> <i class="fas fa-times"></i> 24/7 support </p>
            </div>
            <a href="<?php echo e(url('/select-plan/basic')); ?>" class="btn1">Choose Basic</a>
        </div>

        <div class="box">
            <h3>standard plan</h3>
            <img src="images/price-2.svg" alt="">
            <div class="amount"> <span>$</span>50<span>/month</span> </div>
            <div class="list">
                <p> <i class="fas fa-check"></i> full courses </p>
                <p> <i class="fas fa-check"></i> online exams </p>
                <p> <i class="fas fa-check"></i> certificate </p>
                <p> <i class="fas fa-check"></i> full modules </p>
                <p> <i class="fas fa-times"></i> 24/7 support </p>
            </div>
            <a href="<?php echo e(url('/select-plan/standard')); ?>" class="btn1">Choose Standard</a>
        </div>

        <div class="box">
            <h3>premium plan</h3>
            <img src="images/price-3.svg" alt="">
            <div class="amount"> <span>$</span>90<span>/month</span> </div>
            <div class="list">
                <p> <i class="fas fa-check"></i> full courses </p>
                <p> <i class="fas fa-check"></i> online exams </p>
                <p> <i class="fas fa-check"></i> certificate </p>
                <p> <i class="fas fa-check"></i> full modules </p>
                <p> <i class="fas fa-check"></i> 24/7 support </p>
            </div>
            <a href="<?php echo e(url('/select-plan/premium')); ?>" class="btn1">Choose Premium</a>
        </div>
    </div>
</section>

<!-- pricing section ends -->

<!-- reviews section starts -->
<section class="reviews" id="reviews">
    <div class="container">
        <h2>Course Reviews</h2>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <div class="reviews">
            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="review-card border p-3 mb-2">
                    <p><strong><?php echo e($review->user->name); ?></strong></p>
                    <p><?php echo e($review->review); ?></p> 
                    <small>Posted on <?php echo e($review->created_at->format('d M Y')); ?></small>

                    <?php if(auth()->check() && auth()->user()->role == 'admin'): ?>
                        <form action="<?php echo e(route('reviews.destroy', $review->id)); ?>" method="POST" class="mt-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <form action="<?php echo e(route('reviews.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Course Selection Dropdown -->
            <div class="form-group">
                <label>Select Course:</label>
                <select name="course_id" class="form-control" required>
                    <option value="">Choose A Course</option>
                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($course->id); ?>"><?php echo e($course->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Rating Selection -->
            <div class="form-group">
                <label>Rating:</label>
                <select name="rating" class="form-control" required>
                    <option value="">Select Rating</option>
                    <option value="1">⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>
            </div>

            <!-- Review Textarea -->
            <div class="form-group">
                <textarea name="review" class="form-control" placeholder="Write your review..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
        </form>
    </div>
</section>
<!-- reviews section ends -->

<!-- blogs section starts -->

<section class="blogs" id="blogs" style="background-color: #2196f3;">
    <div class="heading">
        <span>our blogs</span>
        <h3>our untold stories</h3>
    </div>

    <div class="box-container">
        <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="box">
            <img src="<?php echo e(asset('storage/blog-images/'.$blog->image)); ?>" alt="">
            <a href="#" class="title"><?php echo e($blog->title); ?></a>
            <p class="blog-description"><?php echo e($blog->content); ?></p>
            <div class="icons">
                <p><i class="fas fa-calendar"></i><?php echo e($blog->created_at->diffForHumans()); ?></p>
                <a href="#">read more</a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<!-- blogs section ends -->

<!-- JavaScript to Change Color on Selection -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.querySelector('select[name="course_id"]');
        
        courseSelect.addEventListener('change', function() {
            if (this.value !== "") {
                this.classList.add('selected');
            } else {
                this.classList.remove('selected');
            }
        });
    });
</script>

</body>
</html><?php /**PATH C:\Users\govind\Desktop\project\digital_academy\resources\views/frontend/index.blade.php ENDPATH**/ ?>