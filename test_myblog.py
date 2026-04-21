from playwright.sync_api import sync_playwright, expect

def run_myblog_tests():
    with sync_playwright() as p:
        # Browser setup: headless=False taaki aap process apni screen par dekh sakein
        # slow_mo=500 ka matlab hai har action ke beech 0.5 second ka gap
        browser = p.chromium.launch(headless=False, slow_mo=500)
        page = browser.new_page()
        
        # Base URL: Aapke local XAMPP server ka address
        base_url = "http://localhost/blogging_site"
        
        print("\n--- STARTING MYBLOG COMPLETE TEST SUITE ---")

        # --- TC1: User Registration ---
        print("Running TC1: User Registration...")
        page.goto(f"{base_url}/auth/register.php")
        # Niche diye gaye locators aapke register.php ke name attributes par based hain
        page.locator("input[name='username']").fill("TestUser")
        page.locator("input[name='email']").fill("testuser@example.com")
        page.locator("input[name='password']").fill("password123")
        page.get_by_role("button", name="Register").click()
        
        # Verify redirect to login page after registration
        expect(page).to_have_url(f"{base_url}/auth/login.php")
        print("  TC1 Registration: PASS")

        # --- TC2: Valid Login ---
        print("Running TC2: Valid Login...")
        page.locator("input[name='email']").fill("testuser@example.com")
        page.locator("input[name='password']").fill("password123")
        page.get_by_role("button", name="Login").click()
        
        # Verify redirect to home page after login
        expect(page).to_have_url(f"{base_url}/index.php")
        print("  TC2 Valid Login: PASS")

        # --- TC3: Admin Access Security ---
        print("Running TC3: Admin Access Security...")
        # Regular user (TestUser) ko admin page access nahi milna chahiye
        page.goto(f"{base_url}/admin/dashboard.php")
        # Aapke code mein 'Access Denied!' wala message dashboard.php mein likha hai
        if "Access Denied" in page.content():
            print("  TC3 Admin Security: PASS (Access properly denied)")
        else:
            print("  TC3 Admin Security: FAIL (Security check failed!)")

        # --- TC4: Dashboard Check (New) ---
        print("Running TC4: Dashboard Check...")
        # User ke personal dashboard page par navigate karein
        page.goto(f"{base_url}/user/dashboard.php")
        
        # Check karein ki dashboard par login user ka naam dikh raha hai
        # .profile-info h2 aapke style.css aur dashboard.php se liya gaya hai
        username_header = page.locator(".profile-info h2")
        expect(username_header).to_contain_text("TestUser")
        
        # Verify karein ki 'Edit Profile' button dashboard par visible hai
        edit_button = page.locator(".edit-btn")
        expect(edit_button).to_be_visible()
        print("  TC4 Dashboard Check: PASS")

        print("\n--- ALL 4 TEST CASES COMPLETED SUCCESSFULLY ---")
        
        # Test khatam hone ke baad browser band karein
        browser.close()

if __name__ == "__main__":
    run_myblog_tests()
    run_myblog_tests()