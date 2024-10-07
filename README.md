# Yireo FriggaCheckout

**A developer-friendly Magento 2 checkout based on Magewire**

### Features
- Login button in sidebar
- Adding steps based on XML layout
- Regex-based postcode validation (`zip_codes.xml`)
- Order discount coupon
- Support for configuration of number of street lines
- Preview order completion & order summary in sidebar
- Setting required state on fields as configured in the corresponding EAV field
- Change region field (text-input, select-box) based on country selection
- Change required state of postcode depending on `general/country/optional_zip_countries`
- Step validation when going forward
- Navigate steps via progress bar
- VAT ID template switch when country is ID (@todo: Make it optional)
- VAT ID field toggling when company is entered or not (@todo: Make it optional)
- Per field validation based on Magewire
