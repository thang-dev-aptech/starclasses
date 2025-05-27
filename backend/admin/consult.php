<?php
require_once __DIR__ . '/../app/models/ConsultRequest.php';
use App\Models\ConsultRequest;

$consultModel = new ConsultRequest();
$statusFilter = $_GET['status'] ?? 'all';
$allConsults = $consultModel->getAll();
$consults = $statusFilter === 'all' ? $allConsults : array_filter($allConsults, function($c) use ($statusFilter) {
    return $c['status'] === $statusFilter;
});
$consults = array_values($consults); // reindex

// Toast notification (session flash)
$toast = null;
if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    unset($_SESSION['toast']);
}
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/sidebar.php'; ?>

<main class="main-content bg-light">
    <!-- Toast notification -->
    <?php if ($toast): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="consultToast" class="toast align-items-center text-bg-<?= $toast['type'] ?? 'success' ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
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
                var toastEl = document.getElementById('consultToast');
                if (toastEl) toastEl.classList.remove('show');
            }, 3500);
        </script>
    <?php endif; ?>
    <!-- header -->
    <header class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom shadow-sm">
        <div>
            <h1 class="h4 mb-0">Consultations</h1>
            <p class="text-muted small mb-0">Manage consultation requests</p>
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
                <div class="btn-group">
                    <a href="?status=all" class="btn btn-outline-primary<?= $statusFilter==='all'?' active':'' ?>">All</a>
                    <a href="?status=new" class="btn btn-outline-primary<?= $statusFilter==='new'?' active':'' ?>">New</a>
                    <a href="?status=processing" class="btn btn-outline-primary<?= $statusFilter==='processing'?' active':'' ?>">Processing</a>
                    <a href="?status=completed" class="btn btn-outline-primary<?= $statusFilter==='completed'?' active':'' ?>">Completed</a>
                </div>
            </div>
        </div>

        <!-- consult table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 text-center" style="width: 60px;">ID</th>
                                <th class="border-0">Họ và tên</th>
                                <th class="border-0 text-center">Số điện thoại</th>
                                <th class="border-0">Email</th>
                                <th class="border-0 text-center" style="width: 120px;">Loại yêu cầu</th>
                                <th class="border-0 text-center" style="width: 120px;">Trạng thái</th>
                                <th class="border-0 text-center" style="width: 130px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($consults)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="bi bi-chat-square-text" style="font-size:2.5rem;"></i><br>
                                        <span style="font-size:1.1rem;">Chưa có phản hồi nào</span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($consults as $idx => $c): ?>
                                <tr>
                                    <td class="text-center fw-bold text-secondary"><?= $c['id'] ?></td>
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($c['firstname'] . ' ' . $c['lastname']) ?></td>
                                    <td class="text-center text-dark"><?= htmlspecialchars($c['phone']) ?></td>
                                    <td class="text-dark"><?= htmlspecialchars($c['email']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success px-2 py-1">
                                            <i class="bi bi-chat-square-text me-1"></i><?= ucfirst($c['type']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge px-2 py-1 bg-<?= $c['status']==='new'?'warning':'secondary' ?> text-<?= $c['status']==='new'?'warning':'secondary' ?>">
                                            <i class="bi bi-<?= $c['status']==='new'?'clock':($c['status']==='processing'?'hourglass-split':'check-circle') ?> me-1"></i><?= ucfirst($c['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-light" title="Xem" data-bs-toggle="modal" data-bs-target="#viewConsultModal" data-id="<?= $c['id'] ?>">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-light text-success" title="Cập nhật trạng thái" onclick="updateConsultStatus(<?= $c['id'] ?>, 'completed')">
                                                <i class="bi bi-check-lg"></i>
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

<!-- View Consultation Modal -->
<div class="modal fade" id="viewConsultModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Consultation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="consultDetailContent">
                    <div class="text-center text-muted py-4">
                        <div class="spinner-border"></div>
                        <div>Đang tải dữ liệu...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="updateStatusBtn">Cập nhật trạng thái</button>
            </div>
        </div>
    </div>
</div>
<script>
// Dữ liệu consults PHP sang JS
const consultsData = <?= json_encode($consults) ?>;
let currentConsult = null;

// Khi mở modal xem chi tiết
const viewModal = document.getElementById('viewConsultModal');
viewModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const consultId = button.getAttribute('data-id');
    currentConsult = consultsData.find(c => c.id == consultId);
    if (!currentConsult) return;
    // Render nội dung
    let html = `<div class='row'>
        <div class='col-md-6'>
            <div class='mb-2'><b>Họ tên:</b> ${currentConsult.firstname} ${currentConsult.lastname}</div>
            <div class='mb-2'><b>Email:</b> ${currentConsult.email}</div>
            <div class='mb-2'><b>Số điện thoại:</b> ${currentConsult.phone}</div>
            <div class='mb-2'><b>Chủ đề:</b> ${currentConsult.subject}</div>
            <div class='mb-2'><b>Loại yêu cầu:</b> ${currentConsult.type}</div>
        </div>
        <div class='col-md-6'>
            <div class='mb-2'><b>Thời gian gửi:</b> ${currentConsult.created_at}</div>
            <div class='mb-2'><b>Trạng thái hiện tại:</b> <span class='badge bg-${currentConsult.status==='new'?'warning':(currentConsult.status==='processing'?'info':'success')}'>${currentConsult.status}</span></div>
            <div class='mb-2'><b>Nội dung:</b> ${currentConsult.message}</div>
        </div>
    </div>`;
    html += `<div class='mt-3'><label class='form-label'>Cập nhật trạng thái</label>
        <select class='form-select' id='modalConsultStatus'>
            <option value='new' ${currentConsult.status==='new'?'selected':''}>New</option>
            <option value='processing' ${currentConsult.status==='processing'?'selected':''}>Processing</option>
            <option value='completed' ${currentConsult.status==='completed'?'selected':''}>Completed</option>
        </select></div>`;
    document.getElementById('consultDetailContent').innerHTML = html;
});

// Xử lý cập nhật trạng thái (giả lập AJAX, thực tế bạn sẽ cần endpoint xử lý)
document.getElementById('updateStatusBtn').onclick = function() {
    if (!currentConsult) return;
    const newStatus = document.getElementById('modalConsultStatus').value;
    if (newStatus === currentConsult.status) return;
    // Gửi AJAX cập nhật trạng thái (giả lập)
    // TODO: Thay bằng fetch('/api/consult/update', ...) nếu có endpoint thực tế
    setTimeout(() => {
        // Giả lập thành công
        currentConsult.status = newStatus;
        // Đóng modal
        var modal = bootstrap.Modal.getInstance(viewModal);
        modal.hide();
        // Hiện toast
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-bg-success border-0 show position-fixed top-0 end-0 m-3';
        toast.style.zIndex = 9999;
        toast.innerHTML = `<div class='d-flex'><div class='toast-body'>Cập nhật trạng thái thành công!</div><button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast'></button></div>`;
        document.body.appendChild(toast);
        setTimeout(()=>toast.classList.remove('show'), 3000);
        // Reload lại trang để cập nhật bảng (hoặc cập nhật trực tiếp trên giao diện)
        setTimeout(()=>location.reload(), 1000);
    }, 800);
};
</script>

<style>
.table thead th { text-transform: none; font-size: 1rem; letter-spacing: 0.01em; white-space: nowrap; }
.table td, .table th { vertical-align: middle !important; }
.badge { font-size: 0.95em; }
</style>

<?php include __DIR__ . '/partials/footer.php'; ?>