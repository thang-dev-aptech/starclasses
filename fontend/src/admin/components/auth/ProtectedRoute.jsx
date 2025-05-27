// src/admin/components/auth/ProtectedRoute.jsx

import { Navigate } from 'react-router-dom';

const ProtectedRoute = ({ children }) => {
    // Tạm thời đặt thành true để test giao diện
    const isLoggedIn = true;

    if (!isLoggedIn) {
        return <Navigate to="/admin/login" replace />;
    }

    // Luôn cho phép truy cập để hiển thị AdminLayout và các trang con
    return children;
};

export default ProtectedRoute;