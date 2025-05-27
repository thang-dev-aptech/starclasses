import React, { useState } from 'react';
import Header from './Header';
import Sidebar from './Sidebar';
import { Outlet } from 'react-router-dom';
import '@/admin/styles/admin-global.css';
const AdminLayout = () => {
  const [headerContent, setHeaderContent] = useState({
    title : 'Dashboard',
    desc : 'Welcome to Star Classes admin panel'
  });
  return (
    <div className="admin-layout">
        <Sidebar />
        <div className="content-wrapper">
          <Header title={headerContent.title} desc={headerContent.desc}/>
          <main className='page-content'>
            <Outlet context={{setHeaderContent}}/>
          </main>
        </div>
    </div>
  );
};

export default AdminLayout; 