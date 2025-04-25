# CMS Language Inheritance Plugin for Shopware 6

Easily manage multilingual content in Shopware by linking CMS content between languages. With this plugin, you can inherit content from one language to another, reducing duplication and simplifying maintenance.

## ✨ Features

- Inherit CMS content from one language to another (e.g., en-CA shows content from en-US)
- Define inheritance on a per-page or per-CMS block basis
- Clear visual indication in the admin panel when inheritance is active
- Saves time and ensures consistent content across languages

## 🛠️ Use Case

If your webshop has multiple regional English versions (like en-US, en-CA, and en-GB), you can choose to reuse the same CMS content instead of duplicating it. This can be done for an entire CMS page or individual blocks within the page.

Example: Set the en-CA version of a page to inherit content from en-US. The admin view will clearly indicate this link, making it easy to manage.

## 🚀 Installation

Install the plugin via the Shopware Admin or upload it via the CLI:

```bash
composer require laenen/sw6-language-inheritance-plugin
bin/console plugin:install --activate LaenenLanguageInheritance
bin/build-administration.sh
```

## ✅ Compatibility

- Shopware 6.6+
- Works with categories, products and landing pages

## 📷 Screenshots

![Image](https://github.com/user-attachments/assets/956d179b-fdaf-42a8-a7bc-8eb318dc33a2)
![Image](https://github.com/user-attachments/assets/e639b3e2-9aac-4e75-8a99-8c2683cf65f4)
![Image](https://github.com/user-attachments/assets/6085372b-6dcf-420b-99a4-6830c09d2718)

## 🧩 Configuration

1. Navigate to **Catalogue > Categories**
2. Edit a Category
3. For the page or any block in the Layout tab of the Category, select the **"Language Inheritance"** option
4. Choose the source language to inherit content from

## 🧽 Notes

- Inherited content is read-only in the target language. To override it, simply unlink it.
- A visual indicator will show which parts are inherited.