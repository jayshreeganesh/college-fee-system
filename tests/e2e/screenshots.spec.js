const { test, expect } = require('@playwright/test');
const fs = require('fs');

if (!fs.existsSync('screenshots')) {
  fs.mkdirSync('screenshots');
}

const viewports = [
  { name: 'desktop', width: 1920, height: 1080 },
  { name: 'laptop', width: 1366, height: 768 },
  { name: 'tablet-landscape', width: 1024, height: 768 },
  { name: 'tablet-portrait', width: 768, height: 1024 },
  { name: 'mobile', width: 375, height: 812 },
];

test.describe('Comprehensive RWD Screenshots', () => {
  for (const vp of viewports) {
    
    test(`Admin Full Flow - ${vp.name}`, async ({ page }) => {
      await page.setViewportSize({ width: vp.width, height: vp.height });
      
      const dir = `screenshots/${vp.name}`;
      if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });

      // 1. Admin Login Page
      await page.goto('http://localhost:8000/login');
      await page.screenshot({ path: `${dir}/admin-01-login.png`, fullPage: true });
      
      await page.fill('input[name="username"]', 'admin');
      await page.fill('input[name="password"]', 'admin123');
      await page.click('button[type="submit"]');
      
      // 2. Admin Dashboard
      await expect(page.locator('text=Overview')).toBeVisible();
      await page.waitForTimeout(1000);
      await page.screenshot({ path: `${dir}/admin-02-dashboard.png`, fullPage: true });
      
      // 3. Students Page (/admin/users)
      await page.goto('http://localhost:8000/admin/users');
      await page.waitForTimeout(500);
      await page.screenshot({ path: `${dir}/admin-03-students.png`, fullPage: true });
      
      // 4. Fee Categories Page
      await page.goto('http://localhost:8000/admin/fees');
      await page.waitForTimeout(500);
      await page.screenshot({ path: `${dir}/admin-04-fees.png`, fullPage: true });

      // 5. Reports Page (shows transactions)
      await page.goto('http://localhost:8000/admin/reports');
      await page.waitForTimeout(500);
      await page.screenshot({ path: `${dir}/admin-05-reports.png`, fullPage: true });

      // 6. Settings Page
      await page.goto('http://localhost:8000/admin/settings');
      await page.waitForTimeout(500);
      await page.screenshot({ path: `${dir}/admin-06-settings.png`, fullPage: true });
    });

    test(`Student Full Flow - ${vp.name}`, async ({ page }) => {
      await page.setViewportSize({ width: vp.width, height: vp.height });
      
      const dir = `screenshots/${vp.name}`;
      if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });

      // 1. Student Login Page
      await page.goto('http://localhost:8000/student/login');
      await page.screenshot({ path: `${dir}/student-01-login.png`, fullPage: true });
      
      await page.fill('input[name="enrollment_number"]', 'CS2026-001');
      await page.fill('input[name="password"]', 'password123');
      await page.click('button[type="submit"]');
      
      // 2. Student Portal
      await expect(page.locator('text=Total Paid')).toBeVisible();
      await page.waitForTimeout(1000);
      await page.screenshot({ path: `${dir}/student-02-portal.png`, fullPage: true });
    });
  }
});
