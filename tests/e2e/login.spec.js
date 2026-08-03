const { test, expect } = require('@playwright/test');

test.describe('College Fee System E2E', () => {

  test('Admin can log in and view dashboard', async ({ page }) => {
    // Navigate to admin login
    await page.goto('http://localhost:8000/login');
    
    // Fill in credentials
    await page.fill('input[name="username"]', 'admin');
    await page.fill('input[name="password"]', 'admin123');
    
    // Submit form
    await page.click('button[type="submit"]');
    
    // Expect redirection to dashboard and correct title
    await expect(page).toHaveURL('http://localhost:8000/admin');
    await expect(page.locator('h1')).toContainText('Admin Dashboard');
    
    // Check if the "Export Data" button is visible
    await expect(page.locator('text=Export Data')).toBeVisible();
    
    // Logout
    await page.click('text=Logout / Home');
    await expect(page).toHaveURL('http://localhost:8000/');
  });

  test('Student can log in and view portal', async ({ page }) => {
    // Navigate to student login
    await page.goto('http://localhost:8000/student/login');
    
    // Fill in credentials
    await page.fill('input[name="enrollment_number"]', 'CS2026-001');
    await page.fill('input[name="password"]', 'password123');
    
    // Submit form
    await page.click('button[type="submit"]');
    
    // Expect redirection to student portal
    await expect(page).toHaveURL('http://localhost:8000/student');
    await expect(page.locator('h1')).toContainText('Student Portal');
    
    // Check if total paid is visible (should be rendered dynamically from DB)
    await expect(page.locator('text=Total Paid')).toBeVisible();
    
    // Logout
    await page.click('text=Logout');
    await expect(page).toHaveURL('http://localhost:8000/');
  });

});
