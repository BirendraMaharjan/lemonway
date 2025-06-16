=== Lemonway Payment Gateway for WooCommerce ===
Contributors: aegkr, kafleg
Tags: lemonway, payment gateway, woocommerce, dokan, multivendor, private plugin, santerris
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

A private Lemonway payment gateway plugin for WooCommerce, designed for santerris.market with full Dokan multivendor support. Features automatic vendor wallet creation, linking, and updates.

== Description ==

**Lemonway Payment Gateway for WooCommerce** is a custom plugin built exclusively for **santerris.market**.  
It enables customers to pay using **credit/debit cards (Hosted Fields)**, **PayPal (Smart Buttons)**, and **bank wire transfers**—all processed securely via the Lemonway API.

The plugin offers seamless integration with **Dokan Multivendor**, automatically creating and managing Lemonway wallets for each vendor. Vendor data is kept in sync, ensuring accurate payouts and compliance with Lemonway's requirements.

== Features ==

- Custom WooCommerce payment gateway for Lemonway
- Secure card payments via Hosted Fields
- PayPal payments using Lemonway Smart Buttons
- Bank wire transfer support
- Dynamic checkout UI based on payment method
- Lemonway API integration for:
  - Customer payments
  - Vendor wallet management
- Full Dokan multivendor compatibility
- Automatic Lemonway wallet creation for vendors
- Real-time vendor data sync (creation, linking, updates)
- Admin settings for API credentials, environment, and webhook URL
- Webhook listener for transaction status updates
- Future-ready for order splitting and vendor-specific payouts

== Installation ==

1. Upload the `lemonway-payment-gateway` folder to `/wp-content/plugins/`.
2. Activate the plugin in the **Plugins** menu.
3. Configure settings in **WooCommerce > Settings > Payments > Lemonway**:
   - Enter Lemonway API Login and Password
   - Select Sandbox or Live mode
   - Set your webhook URL
4. In **Dokan > Lemonway Settings**, enable vendor wallet creation.
5. Test by placing an order or registering a vendor to verify integration.

== Frequently Asked Questions ==

= Does this plugin support vendor payouts? =  
Yes. Vendor wallets are prepared via Lemonway. Payouts can be managed through Lemonway's mass payout tools.

= What vendor details are synced? =  
Full name, company, address, email, date of birth, nationality, and KYC information required by Lemonway.

= Can vendors update their Lemonway information? =  
Yes. Vendor profile updates in Dokan are automatically synced with Lemonway.

= Is this plugin publicly available? =  
No. This is a private plugin for use on `santerris.market` only.

== Screenshots ==

1. Lemonway gateway settings in WooCommerce
2. Card payment via Hosted Fields at checkout
3. PayPal Smart Buttons at checkout
4. Dokan vendor profile with Lemonway wallet ID
5. Lemonway API logs and sync status

== Upgrade Notice ==

= 1.0.0 =  
Initial release for santerris.market with Dokan multivendor and Lemonway wallet integration.

== Changelog ==

= 1.0.0 – 2025-06-01 =  
* Initial release  
* WooCommerce payment gateway: Card, PayPal, Wire  
* Lemonway API integration  
* Dokan multivendor support  
* Vendor wallet creation, linking, and updates  
* Webhook and transaction status handling