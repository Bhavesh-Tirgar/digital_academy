
<?php $__env->startSection('title', 'Digital Academy'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <h5 class="text-center text-uppercase"><strong>Category list</strong></h5>
        
        <?php if(Session('info')): ?>
            <div class="alert alert-success alert-dismissible show fade">
                <?php echo e(Session('info')); ?>

                <a class="btn btn-close" data-bs-dismiss="alert"></a>
            </div>
        <?php endif; ?>

        <a href="<?php echo e(url('admin/category/create')); ?>" class="btn btn-primary mb-2">
            <i class="fa fa-plus-circle"></i> Add Category
        </a>

        <table class="table table-bordered table-hover">
            <thead class="bg-danger text-center text-light">
                <tr>
                    <th>Index</th>
                    <th>Name</th>
                    <th>Content</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration + ($categories->firstItem() ?? 0) - 1); ?></td>
                    <td><?php echo e($category->name); ?></td>
                    <td><?php echo e($category->description); ?></td>
                    <td><?php echo e(date('d-M-y', strtotime($category->created_at))); ?></td>
                    <td>
                        <form action="<?php echo e(url('admin/category/' . $category->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <a href="<?php echo e(url('admin/category/' . $category->id . '/edit')); ?>" class="btn btn-primary btn-sm rounded-pill">
                                <i class="far fa-edit"></i> Edit
                            </a>
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Are you sure you want to delete?')">
                                <i class="fas fa-trash"></i> Del
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php echo e($categories->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\govind\Desktop\project\digital_academy\resources\views/backend/category/index.blade.php ENDPATH**/ ?>