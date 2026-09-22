# IndiAccounting Software

IndiAccounting is a complete billing, Point of Sale (POS), and double-entry accounting software designed specifically for Indian businesses. 

---

## 🛠 Complete Setup Guide

### 1. System Requirements
- **PHP Version:** PHP 8.2 or PHP 8.3 (Recommended)
- **Database:** MySQL 5.7+ or MariaDB 10.3+
- **Web Server:** Apache (with mod_rewrite enabled) or Nginx
- **Required PHP Extensions:** BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, pdo_mysql, Tokenizer, XML
- **Writable Directories:** `storage/` and `bootstrap/cache/`

### 2. Quick 4-Step Web Installation
1. **Upload Code to Your Web Server**
   - Extract the downloaded `indiaccounting-software-source.zip` package.
   - Upload all files into your web hosting directory (e.g., `public_html` in cPanel, or `htdocs/indiaccounting` in XAMPP).
   - Point your domain or local web server to the project root or `public/` folder.
2. **Launch the 2-Minute Web Installer**
   - Open your browser and visit your site URL: `http://yourdomain.com/install` (or `http://localhost/indiaccounting/public/install`)
   - **Step 1** automatically validates all PHP extensions and directory permissions. Click "Continue to Licensing".
3. **Customer Licensing & Support**
   - Enter your Name, Phone (+91), and Business Email.
   - **Purchase Code:** Enter your official `WEBOT-INDI-REG-XXXX-XXXX-XXXX` key.
   - Click "Verify Code & Continue".
4. **Database Connection & Company Setup**
   - Enter your MySQL credentials (Host, Port, Database Name, User, Password). Click "Test Connection".
   - Enter your Company Name, GSTIN (optional), State, and Admin Password.
   - Click **"Install & Seed IndiAccounting"** to auto-build tables, seed ICAI 28 Groups, and complete setup!

### 3. Default Administrator Account
Use these credentials to access the IndiAccounting dashboard if you did not change them during installation:
- **Login Email:** `admin@indiaccounting.com`
- **Password:** `admin123`
*(You can change your email and password anytime in the Admin Dashboard under Settings > Profile).*

---

## 💡 How It Works (Features & Usage)

IndiAccounting is designed to streamline retail billing, B2B invoicing, and full accounting in one unified system.

### 1. Master Setup (Inventory & Contacts)
Start by adding your core data into the system:
- **Items/Products:** Add your products, set their selling price, assign barcodes, and define GST rates.
- **Customers & Vendors:** Maintain a directory of people you sell to (Customers) and buy from (Vendors).
- **Settings:** Configure your Company Logo, Bank Details (for invoices), and choose your preferred Invoice Theme (Corporate Blue, Modern Slate, Classic Border, Thermal POS).

### 2. Billing & Sales (Two Ways to Sell)
- **Fast Retail POS Counter:** Ideal for retail shops. Open the POS screen, use a barcode scanner or search items quickly, generate a bill, and print it directly to a thermal printer (58mm/80mm). It also generates a dynamic UPI QR code on the receipt for instant payment.
- **Standard Invoices (B2B):** For standard business billing, create an Invoice. The built-in **Indian GST Engine** automatically splits taxes into intra-state (CGST+SGST) or inter-state (IGST) based on the customer's state code.

### 3. Purchases & Expenses
- **Bills/Purchases:** Record stock purchased from vendors as a Purchase Bill. This automatically updates your inventory counts and accounts payable.
- **Expenses:** Record daily operational expenses (e.g., rent, utilities) to maintain an accurate Profit & Loss statement.

### 4. Double-Entry Accounting Vouchers
The software strictly follows standard ICAI 28 group charts of accounts. You can manage your cash flow using standard vouchers:
- **Receipt Voucher:** Record incoming payments from customers.
- **Payment Voucher:** Record outgoing payments to vendors or for expenses.
- **Contra Voucher:** Record internal transfers like cash deposits or withdrawals from the bank.
- **Journal Voucher:** Record adjusting entries between ledgers.

### 5. Reports & GST Filing
- As you record sales, purchases, and expenses, the software automatically builds your **Ledgers, Trial Balance, Profit & Loss Account, and Balance Sheet**.
- **GSTR-1 JSON Export:** At the end of the month, easily export your sales data as a JSON file, ready for direct upload to the Indian GST portal.

---

## Need Professional Setup or Customization?
- Installation Service is available at minimal prices.
- Custom Modules & Multi-Branch Accounting start from Rs 24,999.
- [Chat on WhatsApp for Support](https://wa.me/917002484119?text=Hi%20WebotApp%2C%20I%20want%20to%20order%20IndiAccounting%20services)
