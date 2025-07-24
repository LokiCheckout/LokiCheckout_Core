# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.2] - 24 July 2025
### Fixed
- Split up `coupon_code.phtml` into child templates
- Enhance toggling of coupon code
- Fix var definition in comments of `login.phtml`

## [2.0.1] - 24 July 2025
### Fixed
- Add `aria-invalid` attribute to agreement checkboxes
- Add `required` attribute to agreement checkboxes 
- Set `h-full` instead of flex utils, to prevent resizing image in picture-tag
- Only resize image to width, not width and height
- Rename `jumpTo` to `jumpToMarker`
- Remove obsolete component scripts

## [2.0.0] - 22 July 2025
### Fixed
- Rename PHP namespace from `Yireo_Loki*` to `Loki*`
- Rename composer package from `yireo/magento2-loki*` to `loki/magento2*`
- Bump loki/magento2-components to major 2.0
- Bump loki/magento2-map-components to 0.1
- Bump loki/magento2-field-components to major 2.0
- Move optional CLI and admin features to separate modules

## [1.0.10] - 17 July 2025
### Fixed
- Move title logic from template to XML layout

## [1.0.9] - 08 July 2025
### Fixed
- Remove escaping from SVG output
- Show `error_text` from layout when agreements are not checked yet
- Add `aria-required` to agreements
- Allow retrieving masked quote ID from CheckoutState
- Allow LokiCheckout prefixed modules to be working with Hyva automatically
- Remove the cart drawer from Hyva Themes

## [1.0.8] - 02 July 2025
### Fixed
- Reset agreements after checkout completion
- Properly disabled proceed button if agreements are still unchecked
- Fix final step error when there is no redirect
- Fix Fatal Error with checkout agreements
- Allow HTML in agreements box
- Fixes for block sorting
- Add order-[] class to grid blocks on load
- Remove deprecated getCurrentGrid method
- Remove newline
- Fix PHPMD issues

## [1.0.7] - 20 June 2025
### Fixed
- Finalize new grid settings
- Properly allow overriding and merging of `loki_checkout.xml`
- Add Dutch translations

## [1.0.6] - 20 June 2025
### Fixed
- Undo validation of current step on load

## [1.0.5] - 20 June 2025
### Fixed
- Disable jumpto for now
- Finish new loki-checkout.xml structure
- Rewrite grid layouts to include sub-entity grid

## [1.0.4] - 18 June 2025
### Fixed
- Add loader to coupon code box
- Fixes for Phpstan level 4
- Add escaping of variables
- Move debug icon to SVG file
- Autocomplete fixes for street, region and city
- Use Loki test-case in Playwright to detect JS errors automatically
- Rewrite @helpers to @loki in Playwright tests
- Draft of ApplePay behaviour
- Make sure success URL is a string
- Enhance workflow of errors during final stage
- PHPUnit behaving strange with skipDispatchToCheckout protected variable
- Remove old deprecated trait
- Remove old commented function

## [1.0.3] - 22 May 2025
### Fixed
- Register `Loki_FieldComponents` as a checkout module
- Easier support for image output
- Add label title of payment method code
- Add assertion for usage in PSP integration tests
- Ignore redirect URLs with no length
- Make sure to wait for beforePostNextStep calls before proceeding to next step
- Add proper support for payment instruction text
- Additional checks for final button and component validation
- Trigger component-update event after selecting payment methtod
- Move remove invisible components to LokiComponents
- Double-check if image exists before returning payment icon
- iImprove mechanism to detect tests
- Remove deprecated `use_required_block` setting (which is in FieldComponents now)
- Fix core issue with postcode 1234 allowed by core
- Double-check integration test for postcode validation
- Add CSS wrapper
- Update admin settings with tooltip and regenerate new MODULE.json
- Add tooltip to config values

## [1.0.2] - 13 May 2025
### Fixed
- Fix possible error
- Only dump active modules to loki-checkout.json
- Make sure to include empty modules as well for module dump
- Refactor mechanism to fetch tests from folders
- Fix additional runs of Playwright tests
- Fix warning when array keys are not matched
- Rewrite all beforeInit and afterInit into new initMethod structure
- Rewrite Alpine from initActions object to methods starting with init
- Allow PHP 8.4 in CI
- Remove lines on ES config
- Fix issue with LokiCheckoutMollie DI type overriding core validators
- Do not make BLOCK constant public
- Rewrite integration test flag $skipDispatchToCheckout
- Mobile fixes
- Fix default md:col-span-x
- Fix detection of Hyva/default-csp theme
- Fix type error when no states are required whatsoever
- Luma layout prefix was depending on Luma/blank not Magento/blank, typo
- Prevent error with only optional regions (@RikWillems)

## [1.0.1] - 28 April 2025
### Fixed
- Apply Rector fixes
- Loosen support for magento/module-catalog
- Remove unused imports
- Remove deprecated classes
- Update composer and module deps

## [1.0.0] - 25 April 2025
### Fixed
- Allow upgrading to LokiFieldComponents and LokiComponents 1.0
- Remove `x-model` because of CSP compliance
- Fix wrong saveQuote call
- Failsave for null values in repositories
- CSP fixes
- Add additional Loki modules to Hyva Theme autoregistration
- Add composer suggest
- Customize backdrop of modal dialog
- Draft of fast checkout option for PSPs
- Consolidate vault access in core module
- Intercept unwanted exceptions while rendering
- Optimize guest checkout flags
- Move from `x-init-data` to separate `text/x-loki-init` script to prevent escaping issues
- Implement core customer address validation
- Move Mollie fixture from core to Mollie module
- Fix possible issue when LokiCheckout config is used outside of checkout
- Add lazyloading to cart image
- Finish grid layout JS switching
- Add LokiCheckoutGrid component
- Add empty grid for later usage
- Remove gridClass, add colSpan and mdColSpan in grid layouts
- Allow switching of grid layout per country ID
- Add new Alpine directives x-time, x-datetime, x-date
- Remove CheckoutSession from context
- Remove FieldTemplateLocator from context
- Remove CartRepository from all contexts
- Rewrite `getCartRepository()->save()` to `getCheckoutState()->saveQuote()`
- Reduce dependencies with CheckoutState::saveQuote()
- Make loader icon in fields depend on Alpine and activate only after configurable timeout
- Rename JS validators/ folder to validator/
- Fix invalid HTML in shipping method input

## [0.1.21] - 16 April 2025
### Fixed
- Always persist chosen customer address ID
- When creating a new address, also reset the `customer_address_id` on load
- Change address toggle label on billing step to "Add new billing address"
- Skip shipping address validation for virtual quotes
- Fix issue with one-line street being saved earlier
- Move image from PostNl to core
- Move common templates from PostNL to core
- Unify locations and addresses for any maps
- Turn login into modal popup
- Add JS password complexity validation
- Move scripts from "before.body.end" to new "loki-scripts" container
- Add configurable password complexity
- Rename LokiCheckoutValidator to LokiComponentValidator
- Add handle prefixes for Hyva, Luma and Breeze
- Implement label for gridlayouts
- Add optional countryId to grid XML
- Add label to grid

## [0.1.20] - 10 April 2025
### Fixed
- Exit after redirecting

## [0.1.19] - 10 April 2025
### Fixed
- Use a normal redirect when quote is dead and AJAX is not there

## [0.1.18] - 10 April 2025
### Fixed
- Move dep with "yireo/magento2-hyva-theme-auto-registration" to Hyva module
- Add yireo:loki-checkout:modules:dump command

## [0.1.17] - 09 April 2025
### Fixed
- Restructure loki classes for Luma
- Fix width definition of icon in step button

## [0.1.16] - 09 April 2025
### Fixed
- Switch from TimezoneInterface to Timezone because of DI arg scopeType issue
- Allow switching shipping details in sidebar to alternate template
- Finalize Post NL templating and abstract Shipping classes
- Add ext/intl requirement
- If street is 1 line, explode by space

## [0.1.15] - 08 April 2025
### Fixed
- Working version of Post NL form with saved values and tab selection
- Simplify loading state of target components
- Remove redundant address-select template
- Remove unneeded important flag
- Remove select-icon when loading select-field
- Add warning if coupon code failed to apply
- Add generic Location, Timeframe and Address classes, fi with Post NL
- Mobile fixes

## [0.1.14] - 04 April 2025
### Fixed
- Remove overlay when reloading StepForwardButton component

## [0.1.13] - 04 April 2025
### Fixed
- StepBackwardButtonViewModel was missing required method

## [0.1.12] - 04 April 2025
### Fixed
- Add Playwright functional tests
- Move to Mollie-specific DI config to Mollie plugin
- Allow for specific exceptions to redirect back to checkout/cart
- Remove specific step button templates in favor of FieldComponents
- Modify overlay when loading next step
- Add icons to step button
- Refactoring of Integration Tests
- Refactor FinalStep to get rid of legacy OneStep code
- Disable autocomplete for coupon code
- Implement better DOM ID for shipments too
- Add better DOM ID for payment methods
- Remove `positive_number` filter from telephone and fax
- Fix issue with duplicate DOM IDs again (but now with test)
- First draft of pickup locations
- Resolving of shipping method icons
- Enhance CSS of method listings
- Refactor PaymentRedirectUrl plugin to resolver
- Refactor PaymentMethodIcon plugins into resolvers
- Enable `hide_region_when_optional` by default
- Implement LokiCheckoutRepositoryTestCase
- Enable GitLab CI/CD
- Add help text depending upon `enable_vat_id_only_for_company`
- Refactor BillingAddress and ShippingAddress components to generic Address components
- Use separate template for billing address form title
- Do not ever copy address if the SameAddress service says no

## [0.1.11] - 11 March 2025
### Fixed
- Fix issue with grid CSS classes
- Add better exception message when Context::getStepProvider() is missing

## [0.1.10] - 11 March 2025
### Fixed
- Fix order completion for virtual orders
- Move GridLayout from XML layout to config
- Remove default grid class
- Implement option `loki_checkout/general/default_grid_class`
- Dynamically change title of shipping address form
- Rename `field_name` to `fieldName`
- Fix client-side validation after refactoring to LokiFieldComponents
- Fix age verification in test
- Fix gender validation in test
- Replace PrefixValidator with more generic OptionsValidator
- Add proper date validation of date of birth
- Fix possible error if somebody is spoofing value
- Make sure gender and DOB are required if set so in config
- Accessibility fixes
- Fix styling of country box when there is an error
- Fix location of buttons
- Move required block
- Add module dependencies
- Huge refactoring to move logic into new LokiFieldComponents
- System Check for module dependencies
- Add test for new ViewModel
- Add button label for quote without payment required
- Add default responsiveness to all fields
- Fix name of defaults block
- Re-enable postcode format validator test
- Refactor and adjust styling
- Refactor SystemCheck architecture in admin
- Check for dev modules not being enabled in Production Mode
- Move EmailValidatorTest
- Add missing Dutch translations
- Move email availability in separate validator `email_available`
- Rename `loki-checkout.css_classes` to `loki-components.css_classes`
- Rewrite Alpine.store() APIs
- Add datetime to module overview

## [0.1.9] - 25 February 2025
### Fixed
- Standardize JS event names
- Rename checkout:payment:method-activate to loki-checkout prefix
- Rename loki-checkout-before-redirect to loki-checkout.before-redirect
- Rename loki:dom-update to loki-components:dom-update
- Rename yireo-loki-checkout.component-change to loki-components.component.update
- Add generic LokiComponentsLogger
- Refactor yireoLokiCheckoutLogger to LokiCheckoutLogger object
- StepForwardButton not activated after component updates (use $nextTick)

## [0.1.8] - 24 February 2025
### Fixed
- Rerun LokiCheckoutStepsShippingForwardButton.updateValid() at every update

## [0.1.7] - 24 February 2025
### Fixed
- Hide payment method component by default
- Fix non-existing method NewsletterRepository::getDefaultValue()
- Change Place Order label when virtual quote
- Add missing Dutch translation

## [0.1.6] - 24 February 2025
### Added
- Save in Address Book checkbox
- Add more translation strings

### Fixed
- Prevent null from throwing TypeError / Beautify shipping method label
- Allow saving more variable types
- Call to undefined method getDefaultValue()
- Wrong exception caught
- Prevent saving non-existing payment method still set as default

## [0.1.5] - 24 February 2025
### Fixed
- Prevent exception when selecting shipping method repeatedly
- Allow default value in field to be saved automatically
- Retrieve option model from ViewModel
- Remove empty option for gender
- Remove bgcolor from login card
- Add Dutch translations
- Remove demo field label "Surname"

## [0.1.4] - 23 February 2025
### Fixed
- Intercept exception with addShippingRate

## [0.1.3] - 23 February 2025
### Fixed
- Remove unneeded collectTotals
- Cleanup totals calculation
- Cleanup cart totals rendering
- Failsafe to skip loading shipping methods if country ID is empty
- Make sure default country ID is saved to quote
- Prevent endless loop when submitting to AJAX which then fails
- Simplify shipping method calculation
- Recalculate totals properly via AJAX
- Fix shipping methods test except totals
- Re-enable default shipping method pick
- Dont render address template when HTML is empty anyway
- Add doc link to module overview
- Carrier label not found if carrier no longer active

## [0.1.2] - 22 February 2025
### Fixed
- Update loki_checkout.xml grid CSS for mobile

## [0.1.1] - 22 February 2025
- Small responsive fixes
- Show no billing step title if quote is virtual
- Simplify customer fields block in onestep theme
- Allow customer fields to be shown in either billing or shipping step
- Hide shipping address view and shipping methods for virtual quote
- Hide same-as-billing for virtual quotes
- Empty address was still showing country
- Disable Same as Shipping if quote is virtual
- Allow hiding billing title via XML layout
- Default payment method was not saved to quote
- Lift requirements

## [0.1.0] - 21 February 2025
- Version bump

## [0.0.7] - 21 February 2025
### Added
- New admin page for Module Status
- New admin page for System Checks
- Add reusable RegionProvider

### Fixed
- Set disabled CSS for select as well
- Fix issue with HTML attributes for nested components
- Fix JS numeric validator
- Simplify Agreements handling
- Add DI plugin as workaround for non-working `free_shipping_subtotal`
- Improve streetlines logic with faulty values
- Append streetline values if streetlines config is wrong
- Add "not available" text to unavailable shipping methods
- Add address tag to shipping address preview
- Fix newsletter subscribe for logged in customers
- Rewrite newsletter subscribe logic
- Rename console message from INFO to DEBUG

### Removed
- Remove deprecated `{component_name}` variable for HTML attribute

### Refactor
- Better handling of streetlines
- Rename plugin classes to include `Plugin` suffix
- Refactor LocalMessageRegistry methods
- Refactor `text_select` to avoid unneeded ID suffix

## [0.0.6] - 13 February 2025
### Fixed
- Fix address when customer is logged in and has no addresses to select yet
- Prefix options are separated by semicolumn, not column
- Properly switch shipping method after initial saving
- Fix issue in refreshing other targets
- Fix forms without proper name
- Enhance payment method icon logic
- Redirect to cart when AJAX fails with empty quote
- Load default theme if theme is messed up
- Add proper UnknownThemeException

### Refactor
- Refactor Validator to remove dependency with component
- Huge refactor to move context dependencies in the open
- Move CssClass to LokiComponents

## [0.0.5] - 21 January 2025
- Initial public release

## [0.0.4] - 13 January 2025
- Reach MVP level

## [0.0.3] - 19 December 2024
- Move from Magewire to Loki Components

## [0.0.2] - 2 October 2024
- Copy from GitHub repository to GitLab repository
- Rename to FriggaCheckout and then LokiCheckout

## [0.0.1] - 11 July 2024
- Initial proof-of-concept (MagewireCheckout)
