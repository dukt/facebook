const {test, expect} = require('@playwright/test');

test('Shoud show the Settings page', async ({ page, context, baseURL }) => {
  await page.goto(baseURL + '/facebook/settings');
  const title = page.locator('h1');
  await expect(title).toHaveText('Facebook');
});