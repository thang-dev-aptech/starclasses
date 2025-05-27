// Sidebar functionality
function initializeSidebar() {
    // Get current path
    const currentPath = window.location.pathname;
    
    // Find all nav links in sidebar
    const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
    
    // Remove active class from all links and add to current
    sidebarLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // Handle sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        });
    }

    // Handle hover effect on sidebar items
    sidebarLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            if (!link.classList.contains('active')) {
                link.style.backgroundColor = 'rgba(0,0,0,0.05)';
            }
        });
        
        link.addEventListener('mouseleave', () => {
            if (!link.classList.contains('active')) {
                link.style.backgroundColor = 'transparent';
            }
        });
    });
}

// Dashboard Animations and Effects
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar
    initializeSidebar();
    
    // Animate stats cards on load
    const statsCards = document.querySelectorAll('.card');
    statsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Initialize table controls
    initializeTableControls();
});


//open edit teacher modal
function openEditTeacher(btn) {
    document.querySelector('[name=teacher_name]').value = btn.dataset.teacher_name;
    document.querySelector('[name=category]').value = btn.dataset.category;
    document.querySelector('[name=subject]').value = btn.dataset.subject;
    document.querySelector('[name=experience]').value = btn.dataset.experience;
    document.querySelector('[name=bio]').value = btn.dataset.bio;
    document.querySelector('[name=is_active]').checked = btn.dataset.is_active == '1';
    let editIdInput = document.querySelector('[name=edit_id]');
    if (!editIdInput) {
        editIdInput = document.createElement('input');
        editIdInput.type = 'hidden';
        editIdInput.name = 'edit_id';
        document.getElementById('addTeacherForm').appendChild(editIdInput);
    }
    editIdInput.value = btn.dataset.id;
    var modal = new bootstrap.Modal(document.getElementById('addTeacherModal'));
    modal.show();
}