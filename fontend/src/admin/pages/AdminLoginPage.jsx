
import styles from './AdminLoginPage.module.css';
import logo from '../assets/images/logo_star_classes-Photoroom.png';
import { useEffect } from 'react';
function AdminLoginPage() {
    useEffect(() => {
        document.title = 'Admin Login';
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
    }
    
    return (
        <div className={styles.pageContainer}>
            <div className={styles.header}>
                <img src={logo} alt="Star Classes Logo" className={styles.logo} />
                <h4 className={styles.panelTitle}>ADMIN PANEL</h4>
            </div>
            <div className={styles.loginCard}>
                <h3 className={styles.cardTitle}>Admin Login</h3>
                <form onSubmit={handleSubmit} className={styles.loginForm}>
                    <div className={styles.formGroup}>
                        <label htmlFor="username" className={styles.label}>Username</label>
                        <input
                            type="text"
                            id="username"
                            // value={username}
                            className={styles.input}
                            placeholder="admin" // Placeholder như trong hình
                        />
                    </div>
                    <div className={styles.formGroup}>
                        <label htmlFor="password" className={styles.label}>Password</label>
                        <input
                            type="password"
                            id="password"
                            // value={password}
                            className={styles.input}
                            placeholder="password"
                        />
                    </div>
                    <button
                        type="submit"
                        className={styles.submitButton}
                    >
                        Sign In
                    </button>
                </form>
            </div>
            <footer className={styles.footer}>
                © 2025 Star Classes
            </footer>
        </div>
    );
}

export default AdminLoginPage;