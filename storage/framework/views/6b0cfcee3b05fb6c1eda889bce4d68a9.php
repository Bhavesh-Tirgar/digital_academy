

<?php $__env->startSection('title', 'Digital Academy - Course Materials'); ?>

<?php $__env->startSection('content'); ?>
<style>
    body { background-color: #0d0d0d; color: #f5f5f5; font-family: 'Poppins', sans-serif; }
    .container { max-width: 1100px; margin: auto; padding: 20px; }
    h2 { font-weight: 700; margin-bottom: 5px; color: #00adb5; }
    h3 { font-weight: 500; color: #ffcc00; margin-bottom: 15px; }
    
    .table-container { border-radius: 12px; overflow: hidden; box-shadow: 0px 4px 15px rgba(255,255,255,0.1); }
    .table { width: 100%; border-collapse: collapse; background: #1a1a1a; }
    .table th, .table td { padding: 14px; text-align: left; border-bottom: 1px solid #333; transition: background 0.3s; }
    .table th { background-color: #222; text-transform: uppercase; font-weight: 600; }
    .table tbody tr:hover { background-color: #292929; }

    .btn-delete, .btn-primary, .btn-secondary, .btn-danger { 
        padding: 10px 16px; 
        border: none; 
        border-radius: 6px; 
        cursor: pointer; 
        transition: 0.3s ease-in-out;
        font-weight: 500;
    }
    .btn-delete { background-color: #ff4d4d; color: white; }
    .btn-delete:hover { background-color: #cc0000; transform: scale(1.05); }
    .btn-primary { background-color: #007bff; color: white; }
    .btn-primary:hover { background-color: #0056b3; }
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-secondary:hover { background-color: #545b62; }
    .btn-danger { background-color: #dc3545; color: white; margin-top: 10px; }
    .btn-danger:hover { background-color: #b02a37; }
    
    .preview-img { max-width: 70px; max-height: 70px; cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s; border-radius: 6px; }
    .preview-img:hover { transform: scale(1.15); box-shadow: 0px 4px 12px rgba(255, 255, 255, 0.2); }

    .lightbox { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); text-align: center; justify-content: center; align-items: center; flex-direction: column; }
    .lightbox img { max-width: 90%; max-height: 80vh; margin-top: 5%; border-radius: 12px; }
    .lightbox-close { position: absolute; top: 15px; right: 25px; color: white; font-size: 40px; cursor: pointer; }
</style>

<div class="container">
    <h2>📚 Uploaded Materials for <span><?php echo e($course->name); ?></span></h2>
    <h3>🎓 Plan: <span><?php echo e(ucfirst($plan)); ?></span></h3>

    <?php if($materials->isEmpty()): ?>
        <p style="text-align: center; font-size: 18px; opacity: 0.7;">No materials uploaded yet.</p>
    <?php else: ?>
        <form action="<?php echo e(route('materials.bulkDelete')); ?>" method="POST" id="bulkDeleteForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Preview</th>
                            <th>Download</th>
                            <th>Upload Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><input type="checkbox" name="material_ids[]" value="<?php echo e($material->id); ?>"></td>
                                <td><?php echo e(ucfirst($material->type)); ?></td>
                                <td><?php echo e($material->title); ?></td>
                                <td><?php echo e($material->subject->name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($material->type === 'video'): ?>
                                        <video width="100" controls>
                                            <source src="<?php echo e(asset('storage/' . $material->file_path)); ?>" type="video/mp4">
                                        </video>
                                    <?php elseif($material->type === 'pdf'): ?>
                                        <a href="<?php echo e(asset('storage/' . $material->file_path)); ?>" target="_blank" class="btn btn-secondary">📄 View PDF</a>
                                    <?php elseif($material->type === 'handwritten'): ?>
                                        <img src="<?php echo e(asset('storage/' . $material->file_path)); ?>" class="preview-img" onclick="openLightbox('<?php echo e(asset('storage/' . $material->file_path)); ?>')">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(asset('storage/' . $material->file_path)); ?>" class="btn btn-primary" download>
                                        ⬇ Download
                                    </a>
                                </td>
                                <td><?php echo e($material->created_at->format('d M Y')); ?></td>
                                <td>
                                    <form action="<?php echo e(route('materials.delete', $material->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this material?');">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-danger" onclick="confirmBulkDelete()">🗑 Delete Selected</button>
        </form>
    <?php endif; ?>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox">
    <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
    <img id="lightbox-img">
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    let checkboxes = document.querySelectorAll('input[name="material_ids[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});

function confirmBulkDelete() {
    if (document.querySelectorAll('input[name="material_ids[]"]:checked').length === 0) {
        alert("Please select at least one material to delete.");
    } else if (confirm("Are you sure you want to delete selected materials?")) {
        document.getElementById('bulkDeleteForm').submit();
    }
}

function openLightbox(src) {
    document.getElementById("lightbox-img").src = src;
    document.getElementById("lightbox").style.display = "flex";
}

function closeLightbox() {
    document.getElementById("lightbox").style.display = "none";
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\govind\Desktop\project\digital_academy\resources\views/backend/courses/viewMaterials.blade.php ENDPATH**/ ?>