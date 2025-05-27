import React, { useEffect} from 'react';
import { useOutletContext } from 'react-router-dom';
import StatCard from '../components/ui/StatCard';


const DashboardPage = () => {
  const { setHeaderContent } = useOutletContext();

  

  useEffect(() => {
    setHeaderContent({
      title: 'Dashboard',
      desc: 'Welcome to Star Classes admin panel'
    });
  }, [setHeaderContent]);

  return (
    <div className="main-content container-fluid py-4">
      <div className="row g-3 mb-4 dashboard-stats-row">
        <div className="col-md-4">
          <StatCard title="Total Courses"  icon="book" color="primary" />
        </div>
        <div className="col-md-4">
          <StatCard title="Total Teachers"  icon="person-video3" color="success" />
        </div>
        <div className="col-md-4">
          <StatCard title="New Consultations"  icon="chat-square-text" color="warning" />
        </div>
      </div>
      <div className="row g-3 dashboard-latest-row">
        <div className="col-md-6">
          <div className="card border-0 shadow-sm h-100">
            <div className="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <h5 className="mb-0">Khóa học mới nhất</h5>
              <a href="/admin/courses" className="btn btn-sm btn-primary">Xem tất cả</a>
            </div>
            <div className="card-body p-0">
              <div className="list-group list-group-flush">
                
              </div>
            </div>
          </div>
        </div>
        <div className="col-md-6">
          <div className="card border-0 shadow-sm h-100">
            <div className="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <h5 className="mb-0">Giáo viên mới nhất</h5>
              <a href="/admin/teachers" className="btn btn-sm btn-primary">Xem tất cả</a>
            </div>
            <div className="card-body p-0">
              <div className="list-group list-group-flush">
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className="row g-3 mt-3 dashboard-feedback-row">
        <div className="col-12">
          <div className="card border-0 shadow-sm h-100">
            <div className="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <h5 className="mb-0">Phản hồi mới nhất</h5>
              <a href="/admin/consults" className="btn btn-sm btn-primary">Xem tất cả</a>
            </div>
            <div className="card-body p-0">
              <div className="list-group list-group-flush">
          
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DashboardPage; 