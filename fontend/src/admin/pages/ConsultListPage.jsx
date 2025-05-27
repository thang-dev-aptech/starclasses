import {useOutletContext } from 'react-router-dom'; 
import '@/admin/styles/admin-global.css';
import { useEffect } from 'react';

function ConsultListPage() {
    const { setHeaderContent } = useOutletContext();

    useEffect(() => {
        setHeaderContent({
            title: 'Consult List',
            desc: 'Welcome to Star Classes admin panel'
        });
    }, [setHeaderContent]);
    return (
        <div>
            <h1>Consult List</h1>
        </div>
    );
}

export default ConsultListPage;