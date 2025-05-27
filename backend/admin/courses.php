<?php include __DIR__ . '/partials/header.php'; ?>
<!-- <?php include __DIR__ . '/partials/sidebar.php'; ?> -->
<?php
require_once __DIR__ . '/../app/models/Course.php';
use App\Models\Course;
$courseModel = new Course();

// Xử lý xóa khóa học
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    $courseModel->delete($deleteId);
    header('Location: /admin/courses.php');
    exit();
}

// Lấy dữ liệu khóa học để sửa nếu có edit_id
$editCourse = null;
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    $editCourse = $courseModel->getById($editId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'course_name' => $_POST['course_name'],
        'description' => $_POST['description'],
        'short_description' => $_POST['short_description'] ?? '',
        'price' => $_POST['price'],
        'category' => $_POST['category'],
        'rating' => $_POST['rating'] ?? 0,
        'rating_count' => $_POST['rating_count'] ?? 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'image' => null
    ];
    // Xử lý upload ảnh nếu có
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/courses/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $filepath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
            $data['image'] = $filename;
        }
    }
    if (isset($_POST['edit_id']) && $_POST['edit_id']) {
        // Sửa khóa học
        $courseModel->update((int)$_POST['edit_id'], $data);
    } else {
        // Thêm mới
        $courseModel->create($data);
    }
    header('Location: /admin/courses.php');
    exit();
}

// Xử lý tìm kiếm và lọc
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterCategory = isset($_GET['category']) ? trim($_GET['category']) : '';

// Lấy danh sách khóa học và lọc
$courses = $courseModel->getAll();
if ($search !== '') {
    $courses = array_filter($courses, function($c) use ($search) {
        return stripos($c['course_name'], $search) !== false;
    });
}
if ($filterCategory !== '' && $filterCategory !== 'all') {
    $courses = array_filter($courses, function($c) use ($filterCategory) {
        return strtolower($c['category']) === strtolower($filterCategory);
    });
}
?>
<main class="main-content bg-light">
    <!-- header -->
    <header class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom shadow-sm">
        <div>
            <h1 class="h4 mb-0">Courses</h1>
            <p class="text-muted small mb-0">Manage your tutoring courses</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <button id="toggle-theme" class="btn btn-light rounded-circle" title="Toggle Theme">
                <i class="bi bi-moon"></i>
            </button>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="./assets/images/image.png" alt="Avatar" width="32" height="32" class="rounded-circle me-2">
                    <span>Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/admin/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- main content -->
    <section class="p-4">
        <!-- action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="bi bi-plus-lg me-2"></i>Add New Course
                </button>
            </div>
            <div class="d-flex gap-2">
                <form method="get" class="d-flex gap-2 mb-4" style="max-width: 600px;">
                    <input type="text" name="search" class="form-control" placeholder="Search courses..." value="<?= htmlspecialchars($search) ?>">
                    <select name="category" class="form-select" style="width: 180px;">
                        <option value="all">All Categories</option>
                        <option value="programming" <?= $filterCategory==='programming'?'selected':'' ?>>Programming</option>
                        <option value="design" <?= $filterCategory==='design'?'selected':'' ?>>Design</option>
                        <option value="business" <?= $filterCategory==='business'?'selected':'' ?>>Business</option>
                    </select>
                    <button class="btn btn-outline-primary" type="submit">Lọc</button>
                </form>
            </div>
        </div>

        <!-- courses table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0" style="width: 60px;">ID</th>
                                <th class="border-0">Tên khóa học</th>
                                <th class="border-0">Giá</th>
                                <th class="border-0">Danh mục</th>
                                <th class="border-0">Điểm đánh giá</th>
                                <th class="border-0">Số lượt đánh giá</th>
                                <th class="border-0">Trạng thái</th>
                                <th class="border-0">Ảnh</th>
                                <th class="border-0" style="width: 150px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($courses)): ?>
                            <tr><td colspan="9" class="text-center">Chưa có khóa học nào</td></tr>
                        <?php else: ?>
                            <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= htmlspecialchars($course['id']) ?></td>
                                <td><?= htmlspecialchars($course['course_name']) ?></td>
                                <td><?= number_format($course['price'], 0, ',', '.') ?>đ</td>
                                <td><?= htmlspecialchars($course['category']) ?></td>
                                <td><?= htmlspecialchars($course['rating']) ?></td>
                                <td><?= htmlspecialchars($course['rating_count']) ?></td>
                                <td>
                                    <?php if ($course['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>Hiển thị
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $image = $course['image'] ?? '';
                                    $isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $image);
                                    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/courses/' . $image;
                                    if ($isImage && file_exists($imagePath)) {
                                        echo '<img src="/uploads/courses/' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($course['course_name']) . '" style="width:40px;height:40px;object-fit:cover;">';
                                    } else {
                                        echo '<img src="/assets/no-image.png" alt="Không có ảnh" style="width:40px;height:40px;object-fit:cover;">';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-light" title="Sửa"
                                            data-id="<?= $course['id'] ?>"
                                            data-name="<?= htmlspecialchars($course['course_name']) ?>"
                                            data-short="<?= htmlspecialchars($course['short_description']) ?>"
                                            data-desc="<?= htmlspecialchars($course['description']) ?>"
                                            data-price="<?= htmlspecialchars($course['price']) ?>"
                                            data-category="<?= htmlspecialchars($course['category']) ?>"
                                            data-rating="<?= htmlspecialchars($course['rating']) ?>"
                                            data-rating_count="<?= htmlspecialchars($course['rating_count']) ?>"
                                            data-is_active="<?= $course['is_active'] ?>"
                                            onclick="openEditCourse(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-light text-danger" title="Xóa" onclick="if(confirm('Bạn có chắc chắn muốn xóa?')) location.href='?delete_id=<?= $course['id'] ?>';">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm khóa học mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/courses" id="addCourseForm" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Tên khóa học <span class="text-danger">*</span></label>
                                <input type="text" name="course_name" class="form-control" required value="<?= $editCourse ? htmlspecialchars($editCourse['course_name']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <input type="text" name="short_description" class="form-control" maxlength="255" value="<?= $editCourse ? htmlspecialchars($editCourse['short_description']) : '' ?>">
                                <div class="form-text">Hiển thị trên thẻ khóa học (tối đa 255 ký tự)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="4"><?= $editCourse ? htmlspecialchars($editCourse['description']) : '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Giá khóa học (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" required min="0" step="1000" value="<?= $editCourse ? htmlspecialchars($editCourse['price']) : '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Danh mục (Category) <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" required>
                                    <option value="programming" <?= ($editCourse && $editCourse['category'] === 'programming') ? 'selected' : '' ?>>Programming</option>
                                    <option value="business" <?= ($editCourse && $editCourse['category'] === 'business') ? 'selected' : '' ?>>Business</option>
                                    <option value="design" <?= ($editCourse && $editCourse['category'] === 'design') ? 'selected' : '' ?>>Design</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Điểm đánh giá (Rating)</label>
                                <input type="number" name="rating" class="form-control" min="0" max="5" step="0.1" value="<?= $editCourse ? htmlspecialchars($editCourse['rating']) : '0' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số lượt đánh giá (Rating Count)</label>
                                <input type="number" name="rating_count" class="form-control" min="0" value="<?= $editCourse ? htmlspecialchars($editCourse['rating_count']) : '0' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <div class="form-text">Định dạng: JPG, PNG, GIF. Tối đa 2MB</div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="courseActive" checked value="<?= $editCourse ? ($editCourse['is_active'] ? 'checked' : '') : 'checked' ?>">
                                    <label class="form-check-label" for="courseActive">Hiển thị khóa học</label>
                                </div>
                            </div>
                            <?php if ($editCourse): ?>
                                <input type="hidden" name="edit_id" value="<?= $editCourse['id'] ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="addCourseForm" class="btn btn-primary">Lưu khóa học</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditCourse(btn) {
    document.querySelector('[name=course_name]').value = btn.dataset.name;
    document.querySelector('[name=short_description]').value = btn.dataset.short;
    document.querySelector('[name=description]').value = btn.dataset.desc;
    document.querySelector('[name=price]').value = btn.dataset.price;
    document.querySelector('[name=category]').value = btn.dataset.category;
    document.querySelector('[name=rating]').value = btn.dataset.rating;
    document.querySelector('[name=rating_count]').value = btn.dataset.rating_count;
    document.querySelector('[name=is_active]').checked = btn.dataset.is_active == '1';
    // Gán edit_id vào hidden input
    let editIdInput = document.querySelector('[name=edit_id]');
    if (!editIdInput) {
        editIdInput = document.createElement('input');
        editIdInput.type = 'hidden';
        editIdInput.name = 'edit_id';
        document.getElementById('addCourseForm').appendChild(editIdInput);
    }
    editIdInput.value = btn.dataset.id;
    // Mở modal
    var modal = new bootstrap.Modal(document.getElementById('addCourseModal'));
    modal.show();
}
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>