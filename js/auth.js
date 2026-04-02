/**
 * Authentication Helper Functions
 */

// Check if user is authenticated
async function checkAuth() {
    try {
        const response = await fetch('/api/check_auth.php');
        const data = await response.json();
        return data.authenticated;
    } catch (error) {
        console.error('Auth check failed:', error);
        return false;
    }
}

// Get current user info
async function getCurrentUser() {
    try {
        const response = await fetch('/api/check_auth.php');
        const data = await response.json();
        return data.authenticated ? data.user : null;
    } catch (error) {
        console.error('Get user failed:', error);
        return null;
    }
}

// Logout function
async function logout() {
    try {
        await fetch('/api/logout.php');
        window.location.href = '/login.html';
    } catch (error) {
        console.error('Logout failed:', error);
    }
}

// Redirect to login if not authenticated
async function requireAuth() {
    const isAuthenticated = await checkAuth();
    if (!isAuthenticated) {
        window.location.href = '/login.html';
    }
}
