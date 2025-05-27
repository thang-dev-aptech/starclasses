import React from 'react';

const StatCard = ({ title, value, icon, color, badge, badgeText }) => {
  return (
    <div className="card border-0 shadow-sm h-100 stats-card">
      <div className="card-body">
        <div className="d-flex align-items-center">
          <div className="flex-shrink-0">
            <div className={`stats-icon bg-${color} bg-opacity-10 rounded shadow-sm position-relative`}>
              <i className={`bi bi-${icon} text-${color}`}></i>
              {badge && (
                <span className="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger animate__animated animate__bounceIn">
                  {badge}
                </span>
              )}
            </div>
          </div>
          <div className="flex-grow-1 ms-3">
            <h6 className="text-muted mb-1">{title}</h6>
            <h3 className={`mb-0 fw-bold text-${color}`}>{value}</h3>
            {badgeText && (
              <div className="text-secondary small mt-1">{badgeText}</div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default StatCard; 