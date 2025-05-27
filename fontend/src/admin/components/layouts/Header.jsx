import React from 'react';
import { Link } from 'react-router-dom';

const Header = ({title, desc}) => {

  return (
    <header className="px-3 py-3 bg-white border-bottom shadow-sm">
      <div className="d-flex justify-content-between align-items-center h-100 px-4">
        <div>
          <h1 className="h4 mb-0">{title}</h1>
          <p className="text-muted small mb-0">{desc}</p>
        </div>

        {/* Right side */}
        <div className="d-flex align-items-center gap-3">
          {/* Theme Toggle */}
          <button id="toggle-theme" className="btn btn-light rounded-circle" title="Toggle Theme">
            <i className="bi bi-moon"></i>
          </button>

          {/* User Dropdown */}
          <div className="dropdown">
            <button
              className="btn btn-link text-dark text-decoration-none dropdown-toggle d-flex align-items-center gap-2"
              type="button"
              id="userDropdown"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                src="/src/admin/assets/images/logo_star_classes-Photoroom.png"
                alt="Avatar"
                width="32"
                height="32"
                className="rounded-circle"
              />
              <span>Admin</span>
            </button>
            <ul className="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><hr className="dropdown-divider" /></li>
              <li>
                <Link className="dropdown-item text-danger" to="/admin/logout">
                  <i className="bi bi-box-arrow-right me-2"></i>
                  Logout
                </Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header; 