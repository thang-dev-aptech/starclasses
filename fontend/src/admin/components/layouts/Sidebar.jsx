import React from 'react';
import {NavLink} from 'react-router-dom';
import 'bootstrap-icons/font/bootstrap-icons.css';
const Sidebar = () => {
  

  const menuItems = [
    {
      title: 'Dashboard',
      icon: 'speedometer2',
      path: '/admin/dashboard'
    },
    {
      title: 'Khóa học',
      icon: 'book',
      path: '/admin/courses'
    },
    {
      title: 'Giáo viên',
      icon: 'person-video3',
      path: '/admin/teachers'
    },
    {
      title: 'Phản hồi',
      icon: 'chat-square-text',
      path: '/admin/consults'
    }
  ];

  return (
    <div className="sidebar">
      {/* Logo */}
      <div className="border-bottom">
        <NavLink to="/admin/dashboard">
          <img src="/src/admin/assets/images/logo_star_classes-Photoroom.png" alt="Logo" className="logo"/>
        </NavLink>
      </div>

      {/* Menu */}
      <nav className="p-3">
        <ul className="nav flex-column">
          {menuItems.map((item) => (
            <li className="nav-item" key={item.path}>
              <NavLink
                to={item.path}
                className={({ isActive }) => 
                  `nav-link ${
                    isActive ? 'bg-primary text-white' : 'text-dark' 
                  }`
                  
                }
              
              >
                <i className={`bi bi-${item.icon}`}></i>
                <span className="ms-2">{item.title}</span>
              </NavLink>
            </li>
          ))}
        </ul>
      </nav>
    </div>
  );
};

export default Sidebar; 