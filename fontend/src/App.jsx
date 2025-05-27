
import {BrowserRouter as Router, Routes, Route} from 'react-router-dom'
import 'bootstrap/dist/css/bootstrap.min.css'
import AdminRoutes from './admin/AdminRoutes'
import { Navigate } from 'react-router-dom'

function App() {
  return (
    
      <Routes>
        <Route path="/admin/*" element={<AdminRoutes />} />
        <Route path="/" element={<Navigate to="/admin" replace />} />
      </Routes>
    
  )
}


export default App;
