<?php
session_start();
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../app/models/Teacher.php';
require_once __DIR__ . '/../app/models/Course.php';
require_once __DIR__ . '/../app/models/ConsultRequest.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit();
}

use App\Models\Teacher;
use App\Models\Course;
use App\Models\ConsultRequest;

$teacherModel = new Teacher();
$courseModel = new Course();
$consultModel = new ConsultRequest();

// Thống kê nhanh
$totalCourses = count($courseModel->getAll());
$totalTeachers = count($teacherModel->getAll());
$totalConsults = count($consultModel->getAll(['status' => 'new'])); // Chỉ lấy consult/contact mới

// Danh sách nhanh
$recentCourses = $courseModel->getRecent(5);
$recentTeachers = $teacherModel->getRecent(5);
$recentConsults = $consultModel->getRecent(5);

// Toast notification (session flash)
$toast = null;
if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    unset($_SESSION['toast']);
}
?>
<!-- admin/dashboard.php -->
<?php include __DIR__ . '/partials/header.php'; ?>

<main class="main-content bg-light">
    <!-- Toast notification -->
    <?php if ($toast): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="dashboardToast" class="toast align-items-center text-bg-<?= $toast['type'] ?? 'success' ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= htmlspecialchars($toast['message']) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                var toastEl = document.getElementById('dashboardToast');
                if (toastEl) toastEl.classList.remove('show');
            }, 3500);
        </script>
    <?php endif; ?>
    <!-- header -->
    <header class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom shadow-sm">
        <div>
            <h1 class="h4 mb-0">Dashboard</h1>
            <p class="text-muted small mb-0">Welcome to Star Classes admin panel</p>
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
    <!-- Thông báo phản hồi mới -->
    <?php if ($totalConsults > 0): ?>
      <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-bell-fill me-2"></i>
        Có <b><?= $totalConsults ?></b> phản hồi mới cần xử lý!
        <a href="/admin/consult" class="btn btn-sm btn-primary ms-auto">Xem ngay</a>
      </div>
    <?php endif; ?>

    <!-- main content -->
    <section class="p-4">
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Courses -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-primary bg-opacity-10 rounded shadow-sm">
                                    <i class="bi bi-book text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Total Courses</h6>
                                <h3 class="mb-0 fw-bold text-primary"><?= $totalCourses ?></h3>
                                <?php if ($totalCourses === 0): ?>
                                    <div class="text-danger small mt-1">Chưa có khóa học nào</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Teachers -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-success bg-opacity-10 rounded shadow-sm">
                                    <i class="bi bi-person-video3 text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">Total Teachers</h6>
                                <h3 class="mb-0 fw-bold text-success"><?= $totalTeachers ?></h3>
                                <?php if ($totalTeachers === 0): ?>
                                    <div class="text-danger small mt-1">Chưa có giáo viên nào</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Consultations -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-warning bg-opacity-10 rounded position-relative shadow-sm">
                                    <i class="bi bi-chat-square-text text-warning"></i>
                                    <?php if ($totalConsults > 0): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger animate__animated animate__bounceIn">
                                            <?= $totalConsults ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1">New Consultations</h6>
                                <h3 class="mb-0 fw-bold text-warning"><?= $totalConsults ?></h3>
                                <?php if ($totalConsults === 0): ?>
                                    <div class="text-secondary small mt-1">Chưa có phản hồi mới</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row g-4">
            <!-- Recent Courses -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Khóa học mới nhất</h5>
                            <a href="/admin/courses" class="btn btn-sm btn-primary">Xem tất cả</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if (empty($recentCourses)): ?>
                                <div class="list-group-item text-center text-muted">
                                    <i class="bi bi-book" style="font-size:2rem;"></i><br>Chưa có khóa học nào
                                </div>
                            <?php else: ?>
                                <?php foreach ($recentCourses as $course): ?>
                                    <a href="/admin/courses?edit_id=<?= $course['id'] ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                        <?php
                                        $image = $course['image'] ?? '';
                                        $isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $image);
                                        $imagePath = __DIR__ . '/../storage/' . $image;
                                        ?>
                                        <div class="avatar-circle bg-primary bg-opacity-10">
                                            <?php if ($isImage && file_exists($imagePath)): ?>
                                                <img src="/storage/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($course['course_name']) ?>" style="width:32px;height:32px;object-fit:cover;border-radius:50%;">
                                            <?php else: ?>
                                                <i class="bi bi-book text-primary"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold"><?= htmlspecialchars($course['course_name']) ?></div>
                                            <div class="small text-muted">Danh mục: <?= htmlspecialchars($course['category']) ?></div>
                                        </div>
                                        <span class="badge bg-<?= $course['is_active'] ? 'success' : 'secondary' ?> ms-auto"><?= $course['is_active'] ? 'Active' : 'Inactive' ?></span>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Teachers -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Giáo viên mới nhất</h5>
                            <a href="/admin/teachers" class="btn btn-sm btn-primary">Xem tất cả</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if (empty($recentTeachers)): ?>
                                <div class="list-group-item text-center text-muted">
                                    <i class="bi bi-person-video3" style="font-size:2rem;"></i><br>Chưa có giáo viên nào
                                </div>
                            <?php else: ?>
                                <?php foreach ($recentTeachers as $teacher): ?>
                                    <a href="/admin/teachers?edit_id=<?= $teacher['id'] ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                        <?php
                                        $image = $teacher['image'] ?? '';
                                        $isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $image);
                                        $imagePath = __DIR__ . '/../storage/' . $image;
                                        ?>
                                        <div class="avatar-circle bg-success bg-opacity-10">
                                            <?php if ($isImage && file_exists($imagePath)): ?>
                                                <img src="/storage/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($teacher['teacher_name']) ?>" style="width:32px;height:32px;object-fit:cover;border-radius:50%;">
                                            <?php else: ?>
                                                <i class="bi bi-person-video3 text-success"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold"><?= htmlspecialchars($teacher['teacher_name']) ?></div>
                                            <div class="small text-muted">Môn: <?= htmlspecialchars($teacher['subject']) ?></div>
                                        </div>
                                        <span class="badge bg-<?= $teacher['is_active'] ? 'success' : 'secondary' ?> ms-auto"><?= $teacher['is_active'] ? 'Active' : 'Inactive' ?></span>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Consults -->
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Phản hồi mới nhất</h5>
                            <a href="/admin/consult" class="btn btn-sm btn-primary">Xem tất cả</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if (empty($recentConsults)): ?>
                                <div class="list-group-item text-center text-muted">
                                    <i class="bi bi-chat-square-text" style="font-size:2rem;"></i><br>Chưa có phản hồi nào
                                </div>
                            <?php else: ?>
                                <?php foreach ($recentConsults as $consult): ?>
                                    <div class="list-group-item d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-warning bg-opacity-10">
                                            <i class="bi bi-chat-square-text text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold"><?= htmlspecialchars($consult['firstname'] . ' ' . $consult['lastname']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($consult['subject']) ?> - <?= htmlspecialchars($consult['created_at']) ?></div>
                                        </div>
                                        <span class="badge bg-<?= $consult['status']==='new'?'danger':'secondary' ?> ms-auto"><?= $consult['status']==='new'?'Mới':'Đã xử lý' ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.stats-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s;
}
.stats-card:hover .stats-icon {
    box-shadow: 0 4px 16px rgba(0,0,0,0.10);
}
.toast-container { z-index: 9999; }
</style>

<?php include __DIR__ . '/partials/footer.php'; ?>

