
<?php $__env->startSection('title', 'Digital Academy'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <h5 class="text-center text-uppercase"><strong>Blog List</strong></h5>

        <?php if(session('info')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('info')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(route('blogs.create')); ?>" class="btn btn-primary mb-2">
            <i class="fa fa-plus-circle"></i> Add Blog
        </a>

        <table class="table table-bordered table-hover">
            <thead class="bg-danger text-center text-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-center">
                <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($blog->title); ?></td>
                    <td>
                        <img src="<?php echo e(asset('storage/blog-images/' . $blog->image)); ?>" alt="Blog Image" width="100px">
                    </td>
                    <td><?php echo e(Str::limit($blog->content, 50)); ?></td> 
                    <td><?php echo e($blog->created_at->format('d-M-y')); ?></td>
                    <td>
                        <form action="<?php echo e(route('blogs.destroy', $blog->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <a href="<?php echo e(route('blogs.edit', $blog->id)); ?>" class="btn btn-primary btn-sm rounded-pill">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill"
                                onclick="return confirm('Are you sure you want to delete this blog?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\govind\Desktop\project\digital_academy\resources\views/backend/blog/index.blade.php ENDPATH**/ ?>