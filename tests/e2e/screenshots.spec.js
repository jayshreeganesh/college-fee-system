const { test, expect } = require('@playwright/test');
const fs = require('fs');

// Ensure screenshots directory exists
if (!fs.existsSync('screenshots')) {
  fs.mkdirSync('screenshots');
}

const devices = [
  { name: 'Desktop', width: 1280, height: 720 },
  { name: 'Tablet', width: 768, height: 1024 },
  { name: 'Mobile', width: 375, height: 812 },
];

test.describe('Visual Regression & Responsive Screenshots', () => {
  for (const device of devices) {
    
    test(`Admin Dashboard - ${device.name}`, async ({ page }) => {
      await page.setViewportSize({ width: device.width, height: device.height });
      
      // Login
      await page.goto('http://localhost:8000/login');
      await page.fill('input[name="username"]', 'admin');
      await page.fill('input[name="password"]', 'admin123');
      await page.click('button[type="submit"]');
      
      // Wait for dashboard to load
      await expect(page.locator('text=Overview')).toBeVisible();
      
      // Take Full Page Screenshot
      await page.screenshot({ path: `screenshots/admin-dashboard-${device.name.toLowerCase()}.png`, fullPage: true });
    });

    test(`Student Portal - ${device.name}`, async ({ page }) => {
      await page.setViewportSize({ width: device.width, height: device.height });
      
      // Login
      await page.goto('http://localhost:8000/student/login');
      await page.fill('input[name="enrollment_number"]', 'CS2026-001');
      await page.fill('input[name="password"]', 'password123');
      await page.click('button[type="submit"]');
      
      // Wait for portal to load
      await expect(page.locator('text=Total Paid')).toBeVisible();
      
      // Take Full Page Screenshot
      await page.screenshot({ path: `screenshots/student-portal-${device.name.toLowerCase()}.png`, fullPage: true });
    });
  }
});
