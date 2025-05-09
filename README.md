# Billing Process Engineering

**BPE** is a modular, open-source system for engineering smarter billing experiences across WordPress-based platforms.

It provides a highly flexible foundation and UI framework to manage billing fields, logic, and workflows — tailored for platforms like **WooCommerce**, **EDD**, and beyond.

---

## ✳️ Core Philosophy

BPE is not just a plugin — it’s a way of thinking about billing.

- Clean code, extendable structure
- Modular logic for conditional fields
- UI-first experience with dynamic controls
- Built with performance and compatibility in mind

---

## 🔌 Available Modules

### 📦 `BPE for WooCommerce`
> [View on WordPress.org](https://wordpress.org/plugins/bpe-woo/)

- Disable, enable, or reorder checkout fields.
- Hide address for digital-only orders.
- Dynamic control panel under WooCommerce > Settings > BPE
- Fully compatible with HPOS

### 🧱 `BPE Core`
*(Standalone engine — soon to be released as a separate library)*

- Provides global logic and filter handling
- Field registration, validation & conditional management
- Lightweight and independent of any e-commerce plugin

### 🛍️ `BPE for EDD` *(Coming Soon)*
- Integration with Easy Digital Downloads
- Automatically optimize checkout for digital goods

### 🚀 `BPE Pro` *(In Development)*
- Advanced logic builder (IF/THEN conditions)
- Export/import billing flows
- Role-based field sets
- Developer hooks + REST API

---

## 🛠 Installation

Each module can be installed independently, but they rely on `BPE Core` as the foundation.

1. Clone or download this repo.
2. Upload the desired module(s) to `/wp-content/plugins/`
3. Activate via the WordPress admin.
4. Configure settings via each plugin’s submenu.

---

## 🔐 Compatibility

- ✅ WordPress 6.0+
- ✅ PHP 7.4+
- ✅ WooCommerce 7.0+ (for Woo module)
- ✅ Compatible with HPOS
- 🚫 Not tested with classic checkout plugins

---

## 🤝 Contributing

We welcome feedback, issues, pull requests, and forks.

If you're building your own billing solution, feel free to use `BPE Core` as a base.

### GitHub
- [bpe](https://github.com/samwda/bpe)
- [other modules coming soon...]

---

## 📜 License

All modules in the BPE ecosystem are released under the **GPLv2 or later**.

> Free to use. Free to modify. Built for the WordPress community.

---

## 🌐 Links

- **Official Website:** [samwda.ir](https://samwda.ir)
- **Plugin Page:** [wordpress.org/plugins/bpe-woo](https://wordpress.org/plugins/bpe-woo)
- **Documentation:** (coming soon)

---
